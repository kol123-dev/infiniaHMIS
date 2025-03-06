<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateOpdDiagnosisRequest;
use App\Http\Requests\UpdateOpdDiagnosisRequest;
use App\Models\OpdDiagnosis;
use App\Queries\OpdDiagnosisDataTable;
use App\Repositories\OpdDiagnosisRepository;
use DataTables;
use Illuminate\Http\Request;

class OpdDiagnosisController extends AppBaseController
{
    /** @var OpdDiagnosisRepository */
    private $opdDiagnosisRepository;

    public function __construct(OpdDiagnosisRepository $opdDiagnosisRepo)
    {
        $this->opdDiagnosisRepository = $opdDiagnosisRepo;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of((new OpdDiagnosisDataTable())->get($request->id))->make(true);
        }
    }

    public function store(CreateOpdDiagnosisRequest $request)
    {
        $input = $request->all();
        $this->opdDiagnosisRepository->store($input);
        $this->opdDiagnosisRepository->createNotification($input);

        return $this->sendSuccess(__('messages.opd_diagnosis').' '.__('messages.common.saved_successfully'));
    }

    public function edit(OpdDiagnosis $opdDiagnosis)
    {
        return $this->sendResponse($opdDiagnosis, 'OPD Diagnosis retrieved successfully.');
    }

    public function update(OpdDiagnosis $opdDiagnosis, UpdateOpdDiagnosisRequest $request)
    {
        $this->opdDiagnosisRepository->updateOpdDiagnosis($request->all(), $opdDiagnosis->id);

        return $this->sendSuccess(__('messages.opd_diagnosis').' '.__('messages.common.updated_successfully'));
    }

    public function destroy(OpdDiagnosis $opdDiagnosis)
    {
        $this->opdDiagnosisRepository->deleteOpdDiagnosis($opdDiagnosis->id);

        return $this->sendSuccess(__('messages.opd_diagnosis').' '.__('messages.common.deleted_successfully'));
    }

    public function downloadMedia(OpdDiagnosis $opdDiagnosis)
    {
        $media = $opdDiagnosis->getMedia(OpdDiagnosis::OPD_DIAGNOSIS_PATH)->first();
        if ($media) {
            return $media;
        }

        return '';
    }
}
