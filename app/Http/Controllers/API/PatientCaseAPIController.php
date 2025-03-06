<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Models\PatientCase;
use Illuminate\Http\JsonResponse;

class PatientCaseAPIController extends AppBaseController
{
    public function index(): JsonResponse
    {
        $patientCases = PatientCase::with('doctor')->where('patient_id', getLoggedInUser()->owner_id)->orderBy('id', 'desc')->get();
        $data = [];
        foreach ($patientCases as $patientCase) {
            /** @var PatientCase $patientCase */
            $data[] = $patientCase->preparePatientCase();
        }

        return $this->sendResponse($data, 'Patient Cases Retrieved successfully.');
    }

    public function show($id): JsonResponse
    {
        $patientCase = PatientCase::with('doctor')->where('id', $id)->where('patient_id', getLoggedInUser()->owner_id)->first();

        if (! $patientCase) {
            return $this->sendError(__('messages.patients_cases').' '.__('messages.common.not_found'));
        }

        return $this->sendResponse($patientCase->preparePatientCase(), 'Patient Cases Retrieved successfully.');
    }
}
