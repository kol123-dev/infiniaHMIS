<?php

namespace App\Http\Controllers;

use App\Exports\BedAssignExport;
use App\Http\Requests\CreateBedAssignRequest;
use App\Http\Requests\UpdateBedAssignRequest;
use App\Models\BedAssign;
use App\Models\BedType;
use App\Models\IpdPatientDepartment;
use App\Models\PatientAdmission;
use App\Repositories\BedAssignRepository;
use Carbon\Carbon;
use Flash;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class BedAssignController extends AppBaseController
{
    /** @var BedAssignRepository */
    private $bedAssignRepository;

    public function __construct(BedAssignRepository $bedAssignRepo)
    {
        $this->bedAssignRepository = $bedAssignRepo;
    }

    public function index()
    {
        $data['statusArr'] = BedAssign::STATUS_ARR;

        return view('bed_assigns.index', $data);
    }

    public function create(Request $request)
    {
        $bedId = $request->get('bed_id', null);
        $beds = $this->bedAssignRepository->getBeds();
        $cases = $this->bedAssignRepository->getCases();

        return view('bed_assigns.create', compact('cases', 'beds', 'bedId'));
    }

    public function store(CreateBedAssignRequest $request)
    {
        $input = $request->all();
        $input['status'] = isset($input['status']) ? 1 : 0;
        $IPDPatient = IpdPatientDepartment::with('patient.patientUser')->where('id', $input['ipd_patient_department_id'])->first();
        $input['patient_id'] = $IPDPatient->patient_id;
        $input['birth_date'] = $IPDPatient->patient->patientUser->dob;
        $assign_date = Carbon::parse($input['assign_date'])->toDateString();

        if (! empty($input['birth_date']) && $assign_date < $input['birth_date']) {
            Flash::error(__('messages.bed_assign.assign_date_should_not_be_smaller_than_patient_birth_date'));

            return redirect()->back()->withInput($input);
        }

        $this->bedAssignRepository->store($input);
        $this->bedAssignRepository->createNotification($input);
        Flash::success(__('messages.bed_assign.bed_assign') . ' ' . __('messages.common.saved_successfully'));

        return redirect(route('bed-assigns.index'));
    }

    public function show(BedAssign $bedAssign)
    {
        return view('bed_assigns.show')->with('bedAssign', $bedAssign);
    }

    public function edit(BedAssign $bedAssign)
    {
        $beds = $this->bedAssignRepository->getPatientBeds($bedAssign);
        $cases = $this->bedAssignRepository->getPatientCases($bedAssign);
        $ipd_patient = $bedAssign->ipdPatient ? $bedAssign->ipdPatient->ipd_number : null;

        return view('bed_assigns.edit', compact('cases', 'beds', 'bedAssign', 'ipd_patient'));
    }

    public function update(BedAssign $bedAssign, UpdateBedAssignRequest $request)
    {
        $input = $request->all();
        $input['status'] = isset($input['status']) ? 1 : 0;
        $IPDPatient = IpdPatientDepartment::with('patient.patientUser')->where('id', $input['ipd_patient_department_id'])->latest()->first();
        $input['patient_id'] = $IPDPatient->patient_id;
        $input['birth_date'] = $IPDPatient->patient->patientUser->dob;
        $assign_date = Carbon::parse($input['assign_date'])->toDateString();

        if (! empty($birthDate) && $assign_date < $input['birth_date']) {
            Flash::error(__('messages.bed_assign.assign_date_should_not_be_smaller_than_patient_birth_date'));

            return redirect()->back()->withInput($input);
        }

        $bedAssign = $this->bedAssignRepository->update($input, $bedAssign);
        Flash::success(__('messages.bed_assign.bed_assign') . ' ' . __('messages.common.updated_successfully'));

        return redirect(route('bed-assigns.index'));
    }

    public function destroy(BedAssign $bedAssign)
    {
        $bedAssign->bed->update(['is_available' => 1]);
        $this->bedAssignRepository->delete($bedAssign->id);

        return $this->sendSuccess(__('messages.bed_assign.bed_assign') . ' ' . __('messages.common.deleted_successfully'));
    }

    public function activeDeactiveStatus($id)
    {
        $bedAssign = BedAssign::with('bed')->find($id);
        $status = ! $bedAssign->status;
        $bedAssign->update(['status' => $status]);
        $bedAssign->bed->update(['is_available' => 1]);

        return $this->sendSuccess(__('messages.common.status_updated_successfully'));
    }

    public function bedStatus()
    {
        $bedTypes = BedType::with(['beds.bedAssigns.patient.patientUser'])->get();
        $patientAdmissions = PatientAdmission::whereHas('bed')->with('bed.bedType')->get();

        return view('bed_status.index', compact('bedTypes', 'patientAdmissions'));
    }

    public function bedAssignExport()
    {
        $bedAssign = BedAssign::with('patient.user', 'bed', 'caseFromBedAssign')->get();
        if (!$bedAssign) {
            Flash::error(__('messages.common.no_data_available'));
            redirect(route('bed-assigns.index'));
        }
        return Excel::download(new BedAssignExport, 'bed-assigns-' . time() . '.xlsx');
    }

    public function getIpdPatientsList(Request $request)
    {
        $ipdPatients = $this->bedAssignRepository->getIpdPatients($request->get('id'));

        return $this->sendResponse($ipdPatients, 'Retrieved successfully');
    }
}
