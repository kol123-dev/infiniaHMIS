<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateOperationReportRequest;
use App\Http\Requests\UpdateOperationReportRequest;
use App\Models\OperationReport;
use App\Models\PatientCase;
use App\Repositories\OperationReportRepository;
use Carbon\Carbon;

class OperationReportController extends AppBaseController
{
    /** @var OperationReportRepository */
    private $operationReportRepository;

    public function __construct(OperationReportRepository $operationReportRepo)
    {
        $this->operationReportRepository = $operationReportRepo;
    }

    public function index()
    {
        $doctors = $this->operationReportRepository->getDoctors();
        $cases = $this->operationReportRepository->getCases();

        return view('operation_reports.index', compact('doctors', 'cases'));
    }

    public function store(CreateOperationReportRequest $request)
    {
        $input = $request->all();
        $patientId = PatientCase::with('patient.patientUser')->whereCaseId($input['case_id'])->first();
        $birthDate = $patientId->patient->patientUser->dob;
        $operationDate = Carbon::parse($input['date'])->toDateString();

        if (! empty($birthDate) && $operationDate < $birthDate) {
            return $this->sendError(__('messages.operation_report.data_should_not_be_smaller_than_patient_birth_date'));
        }

        $this->operationReportRepository->store($input);

        return $this->sendSuccess(__('messages.operation_report.operation_report').' '.__('messages.common.saved_successfully'));
    }

    public function show(OperationReport $operationReport)
    {
        $doctors = $this->operationReportRepository->getDoctors();
        $cases = $this->operationReportRepository->getCases();

        return view('operation_reports.show')->with([
            'operationReport' => $operationReport, 'doctors' => $doctors, 'cases' => $cases,
        ]);
    }

    public function edit(OperationReport $operationReport)
    {
        if (getLoggedinDoctor() && checkRecordAccess($operationReport->doctor_id)) {
            return $this->sendError(__('messages.operation_report.operation_report').' '.__('messages.common.not_found'));
        } else {
            return $this->sendResponse($operationReport, 'Operation Report retrieved successfully.');
        }
    }

    public function update(OperationReport $operationReport, UpdateOperationReportRequest $request)
    {
        $input = $request->all();
        $patientId = PatientCase::with('patient.patientUser')->whereCaseId($input['case_id'])->first();
        $birthDate = $patientId->patient->patientUser->dob;
        $operationDate = Carbon::parse($input['date'])->toDateString();

        if (! empty($birthDate) && $operationDate < $birthDate) {
            return $this->sendError(__('messages.operation_report.data_should_not_be_smaller_than_patient_birth_date'));
        }

        $this->operationReportRepository->update($input, $operationReport);

        return $this->sendSuccess(__('messages.operation_report.operation_report').' '.__('messages.common.updated_successfully'));
    }

    public function destroy(OperationReport $operationReport)
    {
        $operationReport->delete();

        return $this->sendSuccess(__('messages.operation_report.operation_report').' '.__('messages.common.deleted_successfully'));
    }
}
