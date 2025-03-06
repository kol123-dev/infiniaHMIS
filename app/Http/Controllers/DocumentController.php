<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateDocumentRequest;
use App\Http\Requests\UpdateDocumentRequest;
use App\Models\Document;
use App\Repositories\DocumentRepository;
use Flash;
use Storage;
use Str;

class DocumentController extends AppBaseController
{
    /** @var DocumentRepository */
    private $documentRepository;

    public function __construct(DocumentRepository $documentRepo)
    {
        $this->documentRepository = $documentRepo;
    }

    public function index()
    {
        $data = $this->documentRepository->getSyncList();

        return view('documents.index')->with($data);
    }

    public function store(CreateDocumentRequest $request)
    {
        $input = $request->all();

        $this->documentRepository->store($input);

        return $this->sendSuccess(__('messages.document.document').' '.__('messages.common.saved_successfully'));
    }

    public function show(Document $document)
    {
        $documents = $this->documentRepository->find($document->id);
        $data = $this->documentRepository->getSyncList();
        $data['documents'] = $documents;

        return view('documents.show')->with($data);
    }

    public function edit(Document $document)
    {
        if (getLoggedinPatient() && checkRecordAccess($document->patient_id)) {
            return $this->sendError(__('messages.document.document').' '._('messages.common.not_found'));
        } else {
            return $this->sendResponse($document, 'Document retrieved successfully.');
        }
    }

    public function update(Document $document, UpdateDocumentRequest $request)
    {
        $this->documentRepository->updateDocument($request->all(), $document->id);

        return $this->sendSuccess(__('messages.document.document').' '.__('messages.common.updated_successfully'));
    }

    public function destroy(Document $document)
    {
        if (getLoggedinPatient() && checkRecordAccess($document->patient_id)) {
            return $this->sendError(__('messages.document.document').' '._('messages.common.not_found'));
        } else {
            $this->documentRepository->deleteDocument($document->id);

            return $this->sendSuccess(__('messages.document.document').' '.__('messages.common.deleted_successfully'));
        }
    }

    public function downloadMedia(Document $document)
    {
        if (getLoggedinPatient() && checkRecordAccess($document->patient_id)) {
            Flash::error(__('messages.document.document').' '.__('messages.common.not_found'));

            return redirect(route('documents.index'));
        } else {
            $documentMedia = $document->media[0];
            $documentPath = $documentMedia->getPath();

            if (config('app.media_disc') === 'public') {
                $documentPath = (Str::after($documentMedia->getUrl(), '/uploads'));
            }

            $file = Storage::disk(config('app.media_disc'))->get($documentPath);

            $headers = [
                'Content-Type' => $document->media[0]->mime_type,
                'Content-Description' => 'File Transfer',
                'Content-Disposition' => "attachment; filename={$document->media[0]->file_name}",
                'filename' => $document->media[0]->file_name,
            ];

            return response($file, 200, $headers);
        }
    }
}
