<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateIpdDiagnosisRequest;
use App\Http\Requests\UpdateIpdDiagnosisRequest;
use App\Models\IpdDiagnosis;
use App\Queries\IpdDiagnosisDataTable;
use App\Repositories\IpdDiagnosisRepository;
use DataTables;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class IpdDiagnosisController extends AppBaseController
{
    /** @var IpdDiagnosisRepository */
    private $ipdDiagnosisRepository;

    public function __construct(IpdDiagnosisRepository $ipdDiagnosisRepo)
    {
        $this->ipdDiagnosisRepository = $ipdDiagnosisRepo;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of((new IpdDiagnosisDataTable())->get($request->id))->make(true);
        }
    }

    public function store(CreateIpdDiagnosisRequest $request)
    {
        $input = $request->all();
        $this->ipdDiagnosisRepository->store($input);

        return $this->sendSuccess(__('messages.ipd_diagnosis').' '.__('messages.common.saved_successfully'));
    }

    public function edit(IpdDiagnosis $ipdDiagnosis)
    {
        return $this->sendResponse($ipdDiagnosis, 'IPD Diagnosis retrieved successfully.');
    }

    public function update(IpdDiagnosis $ipdDiagnosis, UpdateIpdDiagnosisRequest $request)
    {
        $this->ipdDiagnosisRepository->updateIpdDiagnosis($request->all(), $ipdDiagnosis->id);

        return $this->sendSuccess(__('messages.ipd_diagnosis').' '.__('messages.common.updated_successfully'));
    }

    public function destroy(IpdDiagnosis $ipdDiagnosis)
    {
        $this->ipdDiagnosisRepository->deleteIpdDiagnosis($ipdDiagnosis->id);

        return $this->sendSuccess(__('messages.ipd_diagnosis').' '.__('messages.common.deleted_successfully'));
    }

    public function downloadMedia(IpdDiagnosis $ipdDiagnosis)
    {
        $media = $ipdDiagnosis->getMedia(IpdDiagnosis::IPD_DIAGNOSIS_PATH)->first();

        if ($media != null) {
            $media = $media->id;
            $mediaItem = Media::find($media);

            return $mediaItem;
        }

        return '';
    }
}
