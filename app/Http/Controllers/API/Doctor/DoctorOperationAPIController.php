<?php

namespace App\Http\Controllers\API\Doctor;

use App\Http\Controllers\AppBaseController;
use App\Models\OperationReport;
use App\Models\PatientCase;

class DoctorOperationAPIController extends AppBaseController
{
    public function index(): \Illuminate\Http\JsonResponse
    {
        $operation_reports = OperationReport::with('patient', 'doctor', 'caseFromOperationReport')->where('doctor_id',
            getLoggedInUser()->owner_id)->orderBy('id', 'desc')->get();

        $data = [];
        foreach ($operation_reports as $operation_report) {
            $data[] = $operation_report->prepareData();
        }

        return $this->sendResponse($data, 'Operation report retrieved successfully');
    }

    public function show($caseId): \Illuminate\Http\JsonResponse
    {
        $case_detail = PatientCase::where('case_id', $caseId)->where('doctor_id', getLoggedInUser()->owner_id)->first();

        if (! $case_detail) {
            return $this->sendError(__('messages.patients_cases').' '.__('messages.common.not_found'));
        }

        return $this->sendResponse($case_detail->preparePatientCaseDetailData(), 'Patient case retrieve successfully');
    }

    public function delete($id): \Illuminate\Http\JsonResponse
    {
        $operation_report = OperationReport::with('patient', 'doctor', 'caseFromOperationReport')->where('id', $id)->where('doctor_id', getLoggedInUser()->owner_id)->first();

        if (! $operation_report) {
            return $this->sendError(__('messages.operation_report.operation_report').' '.__('messages.common.not_found'));
        }

        $operation_report->delete();

        return $this->sendSuccess(__('messages.operation_report.operation_report').' '.__('messages.common.deleted_successfully'));
    }
}
