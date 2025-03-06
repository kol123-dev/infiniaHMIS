<?php

namespace App\Repositories;

use App\Models\Document;
use App\Models\DocumentType;
use App\Models\Patient;
use Auth;
use Carbon\Carbon;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Class DocumentRepository
 *
 * @version February 18, 2020, 9:22 am UTC
 */
class DocumentRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'title',
        'document_type_id',
        'media_id',
        'patient_id',
    ];

    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    public function model()
    {
        return Document::class;
    }

    public function getSyncList()
    {
        $user = Auth::user();
        if ($user->hasRole('Doctor')) {
            $data['patients'] = getPatientsList($user->owner_id);
        } else {
            if (! $user->hasRole('Patient')) {
                $data['patients'] = Patient::getActivePatientNames()->toArray();
            }
        }

        $data['documentType'] = DocumentType::select(['name', 'id'])->toBase()->pluck('name', 'id')->toArray();

        return $data;
    }

    public function store($input)
    {
        try {
            $input['uploaded_by'] = Auth::id();
            if (getLoggedinPatient()) {
                $input['patient_id'] = getLoggedInUser()->owner_id;
            }

            $document = $this->create($input);
            if (isset($input['file']) && ! empty($input['file'])) {
                $document->addMedia($input['file'])->toMediaCollection(Document::PATH, config('app.media_disc'));
            }
        } catch (\Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function updateDocument($input, $documentId)
    {
        try {
            $document = $this->update($input, $documentId);
            if (isset($input['file']) && ! empty($input['file'])) {
                if ($document->media->first()) {
                    $document->deleteMedia($document->media->first()->id);
                }
                $document->addMedia($input['file'])->toMediaCollection(Document::PATH, config('app.media_disc'));
                $document->update(['updated_at' => Carbon::now()->timestamp]);
            }
        } catch (\Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function deleteDocument($documentId)
    {
        try {
            $document = $this->find($documentId);
            if ($document->media->first()) {
                $document->deleteMedia($document->media->first()->id);
            }
            $this->delete($documentId);
        } catch (\Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }
}
