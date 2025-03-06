<?php

namespace App\Http\Controllers;

use App\Exports\BloodIssueExport;
use App\Http\Requests\BloodIssueRequest;
use App\Models\BloodDonor;
use App\Models\BloodIssue;
use App\Repositories\BloodIssueRepository;
use App\Repositories\PatientCaseRepository;
use Exception;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Flash;

class BloodIssueController extends AppBaseController
{
    /** @var BloodIssueRepository */
    private $bloodIssueRepository;

    /** @var PatientCaseRepository */
    private $patientCaseRepository;

    public function __construct(
        BloodIssueRepository $bloodIssueRepository,
        PatientCaseRepository $patientCaseRepository
    ) {
        $this->bloodIssueRepository = $bloodIssueRepository;
        $this->patientCaseRepository = $patientCaseRepository;
    }

    public function index()
    {
        $doctors = $this->patientCaseRepository->getDoctors();
        $patients = $this->patientCaseRepository->getPatients();
        $donors = BloodDonor::orderBy('name')->pluck('name', 'id');

        return view('blood_issues.index', compact('doctors', 'patients', 'donors'));
    }

    public function store(BloodIssueRequest $request)
    {
        try {
            $input = $request->all();
            $input['amount'] = removeCommaFromNumbers($input['amount']);
            $this->bloodIssueRepository->create($input);

            return $this->sendSuccess(__('messages.blood_issues') . ' ' . __('messages.common.saved_successfully'));
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function getBloodGroup(Request $request)
    {
        try {
            $bloodGroup = $this->bloodIssueRepository->getBloodGroup($request->get('id'));

            return $this->sendResponse($bloodGroup, 'Blood Group Retrieved successfully.');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function edit(BloodIssue $bloodIssue)
    {
        return $this->sendResponse($bloodIssue, 'Blood Issue retrieved successfully.');
    }

    public function update(BloodIssueRequest $request, BloodIssue $bloodIssue)
    {
        try {
            $input = $request->all();
            $input['amount'] = removeCommaFromNumbers($input['amount']);
            $this->bloodIssueRepository->update($input, $bloodIssue->id);

            return $this->sendSuccess(__('messages.blood_issues') . ' ' . __('messages.common.updated_successfully'));
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function destroy(BloodIssue $bloodIssue)
    {
        try {
            $bloodIssue->delete();

            return $this->sendSuccess(__('messages.blood_issues') . ' ' . __('messages.common.deleted_successfully'));
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function export()
    {
        $bloodIssue = BloodIssue::all();
        if (!$bloodIssue) {
            Flash::error(__('messages.common.no_data_available'));
            return redirect(route('blood-issues.index'));
        }
        return Excel::download(new BloodIssueExport, 'blood-issue-' . time() . '.xlsx');
    }
}
