<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateInvestigationReportRequest;
use App\Http\Requests\UpdateInvestigationReportRequest;
use App\Models\InvestigationReport;
use App\Models\Patient;
use App\Repositories\InvestigationReportRepository;
use Carbon\Carbon;
use Flash;
use Storage;
use Str;

class InvestigationReportController extends AppBaseController
{
    /** @var InvestigationReportRepository */
    private $investigationReportRepository;

    public function __construct(InvestigationReportRepository $investigationReportRepo)
    {
        $this->investigationReportRepository = $investigationReportRepo;
    }

    public function index()
    {
        $data['statusArr'] = InvestigationReport::STATUS_ARR;

        return view('investigation_reports.index', $data);
    }

    public function create()
    {
        $status = InvestigationReport::STATUS;
        $patients = $this->investigationReportRepository->getPatients();
        $doctors = $this->investigationReportRepository->getDoctors();

        return view('investigation_reports.create', compact('status', 'patients', 'doctors'));
    }

    public function store(CreateInvestigationReportRequest $request)
    {
        $input = $request->all();
        $patientId = Patient::with('patientUser')->whereId($input['patient_id'])->first();
        $birthDate = $patientId->patientUser->dob;
        $reportDate = Carbon::parse($input['date'])->toDateString();

        if (! empty($birthDate) && $reportDate < $birthDate) {
            Flash::error(_('messages.investigation_report.birth_date_validation'));

            return redirect()->back()->withInput($input);
        }

        $this->investigationReportRepository->store($input);
        Flash::success(__('messages.investigation_report.investigation_report').' '.__('messages.common.saved_successfully'));

        return redirect(route('investigation-reports.index'));
    }

    public function show(InvestigationReport $investigationReport)
    {
        $investigationReport = $this->investigationReportRepository->find($investigationReport->id);

        if (empty($investigationReport)) {
            Flash::error(_('messages.investigation_report').''.__('messages.common.not_found'));

            return redirect(route('investigation-reports.index'));
        }

        return view('investigation_reports.show')->with('investigationReport', $investigationReport);
    }

    public function edit(InvestigationReport $investigationReport)
    {
        if (checkRecordAccess($investigationReport->doctor_id)) {
            return view('errors.404');
        } else {
            $status = InvestigationReport::STATUS;
            $patients = $this->investigationReportRepository->getPatients();
            $doctors = $this->investigationReportRepository->getDoctors();
            $fileExt = pathinfo($investigationReport->attachment_url, PATHINFO_EXTENSION);

            return view('investigation_reports.edit',
                compact('investigationReport', 'status', 'patients', 'doctors', 'fileExt'));
        }
    }

    public function update(InvestigationReport $investigationReport, UpdateInvestigationReportRequest $request)
    {
        if (empty($investigationReport)) {
            Flash::error(_('messages.investigation_report').''.__('messages.common.not_found'));

            return redirect(route('investigation-reports.index'));
        }

        $input = $request->all();
        $patientId = Patient::with('patientUser')->whereId($input['patient_id'])->first();
        $birthDate = $patientId->patientUser->dob;
        $reportDate = Carbon::parse($input['date'])->toDateString();

        if (! empty($birthDate) && $reportDate < $birthDate) {
            Flash::error(__('messages.investigation_report.birth_date_validation'));

            return redirect()->back()->withInput($input);
        }

        $this->investigationReportRepository->update($input, $investigationReport->id);
        Flash::success(__('messages.investigation_report.investigation_report').' '.__('messages.common.updated_successfully'));

        return redirect(route('investigation-reports.index'));
    }

    public function destroy(InvestigationReport $investigationReport)
    {
        if (checkRecordAccess($investigationReport->doctor_id)) {
            return $this->sendError(__('messages.investigation_report.investigation_report').' '.__('messages.common.not_found'));
        } else {
            $investigationReport->delete();

            return $this->sendSuccess(__('messages.investigation_report.investigation_report').' '.__('messages.common.deleted_successfully'));
        }
    }

    public function downloadMedia(InvestigationReport $investigationReport)
    {
        $documentMedia = $investigationReport->media[0];
        $documentPath = $documentMedia->getPath();

        if (config('app.media_disc') === 'public') {
            $documentPath = (Str::after($documentMedia->getUrl(), '/uploads'));
        }

        $file = Storage::disk(config('app.media_disc'))->get($documentPath);

        $headers = [
            'Content-Type' => $investigationReport->media[0]->mime_type,
            'Content-Description' => 'File Transfer',
            'Content-Disposition' => "attachment; filename={$investigationReport->media[0]->file_name}",
            'filename' => $investigationReport->media[0]->file_name,
        ];

        return response($file, 200, $headers);
    }
}
