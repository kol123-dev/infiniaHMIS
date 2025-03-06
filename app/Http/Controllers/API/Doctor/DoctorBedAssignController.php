<?php

namespace App\Http\Controllers\API\Doctor;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\CreateBedAssignRequest;
use App\Models\Bed;
use App\Models\BedAssign;
use App\Models\BedType;
use App\Models\IpdPatientDepartment;
use App\Models\PatientCase;
use App\Repositories\BedAssignRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DoctorBedAssignController extends AppBaseController
{
    /** @var BedAssignRepository */
    private $bedAssignRepository;

    public function __construct(BedAssignRepository $bedAssignRepo)
    {
        $this->bedAssignRepository = $bedAssignRepo;
    }

    public function index(): \Illuminate\Http\JsonResponse
    {
        $bed_assigns = BedAssign::with('patient.patientUser', 'bed', 'caseFromBedAssign', 'ipdPatient')->orderBy('id', 'desc')->get();
        $data = [];
        foreach ($bed_assigns as $bed_assign) {
            $data[] = $bed_assign->prepareData();
        }

        return $this->sendResponse($data, 'Bed Assigns Retrieved Successfully');
    }

    public function filter(Request $request): \Illuminate\Http\JsonResponse
    {
        $status = $request->get('status');

        if ($status == 'all') {
            $bed_assigns = BedAssign::with('patient.patientUser', 'bed', 'caseFromBedAssign', 'ipdPatient')->orderBy('id', 'desc')->get();
            $data = [];
            foreach ($bed_assigns as $bed_assign) {
                $data[] = $bed_assign->prepareData();
            }

            return $this->sendResponse($data, 'Bed Assigns Retrieved Successfully');
        } elseif ($status == 'active') {
            $bed_assigns = BedAssign::where('status', BedAssign::ACTIVE)->with('patient.patientUser', 'bed',
                'caseFromBedAssign', 'ipdPatient')->orderBy('id', 'desc')->get();
            $data = [];
            foreach ($bed_assigns as $bed_assign) {
                $data[] = $bed_assign->prepareData();
            }

            return $this->sendResponse($data, 'Bed Assigns Retrieved Successfully');
        } else {
            $bed_assigns = BedAssign::where('status', BedAssign::INACTIVE)->with('patient.patientUser', 'bed',
                'caseFromBedAssign', 'ipdPatient')->orderBy('id', 'desc')->get();
            $data = [];
            foreach ($bed_assigns as $bed_assign) {
                $data[] = $bed_assign->prepareData();
            }

            return $this->sendResponse($data, 'Bed Assigns Retrieved Successfully');
        }
    }

    public function show($id): \Illuminate\Http\JsonResponse
    {
        $bed_assign = BedAssign::where('id', $id)->with('patient.patientUser', 'bed',
            'caseFromBedAssign', 'ipdPatient')->first();

        return $this->sendResponse($bed_assign->prepareBedAssignData(), 'Bed Assigns Retrieved Successfully');
    }

    public function patientCase(): \Illuminate\Http\JsonResponse
    {
        $patient_cases = PatientCase::whereDoesntHave('bedAssign')->with('patient.patientUser')->where('doctor_id', '=',
            getLoggedInUser()->owner_id)->where('status', '=', 1)->orderBy('id', 'desc')->get();
        $data = [];
        foreach ($patient_cases as $patient_case) {
            $data[] = $patient_case->prepareData();
        }

        return $this->sendResponse($data, 'Patient Cases Retrieved Successfully');
    }

    public function ipdPatient($caseId): \Illuminate\Http\JsonResponse
    {
        $patientCase = PatientCase::where('case_id', $caseId)->value('id');

        $ipd_patients = IpdPatientDepartment::where('doctor_id',
            getLoggedInUser()->owner_id)->whereCaseId($patientCase)->select(['id', 'ipd_number'])->orderBy('id', 'desc')->get();

        return $this->sendResponse($ipd_patients, 'IPD Patient Retrieved Successfully');
    }

    public function getBeds(): \Illuminate\Http\JsonResponse
    {
        $beds = Bed::where('is_available', 1)->select(['id', 'name'])->orderBy('id', 'desc')->get();

        return $this->sendResponse($beds, 'IPD Patient Retrieved Successfully');
    }

    public function getEditBeds(Request $request): \Illuminate\Http\JsonResponse
    {
        $beds = Bed::where('id', $request->bed_id)->orWhere('is_available', 1)->select(['id', 'name'])->get();

        return $this->sendResponse($beds, 'IPD Patient Retrieved Successfully');
    }

    public function store(CreateBedAssignRequest $request): \Illuminate\Http\JsonResponse
    {
        $input = $request->all();
        $input['status'] = 1;
        $IPDPatient = IpdPatientDepartment::with('patient.patientUser')->where('id', $input['ipd_patient_department_id'])->first();
        $input['patient_id'] = $IPDPatient->patient_id;
        $birthDate = $IPDPatient->patient->patientUser->dob;
        $assign_date = Carbon::parse($input['assign_date'])->toDateString();
        if (! empty($birthDate) && $assign_date < $birthDate) {
            return $this->sendResponse($input, 'Bed Assign date should not be smaller than patient birth date');
        }
        $this->bedAssignRepository->store($input);

        return $this->sendSuccess(__('messages.common.bed_assigned_successfully'));
    }

    public function edit($id): \Illuminate\Http\JsonResponse
    {
        $bedAssign = BedAssign::where('id', $id)->first();

        return $this->sendResponse($bedAssign->dataForEdit(), 'Bed Assign Retrieved Successfully');
    }

    public function update(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        $input = $request->all();
        $bedAssign = BedAssign::where('id', $id)->first();
        $patientId = PatientCase::with('patient.patientUser')->whereCaseId($input['case_id'])->first();
        $birthDate = $patientId->patient->patientUser->dob;
        //        $assign_date = Carbon::parse($input['assign_date'])->toDateString();
        $assign_date = \Carbon\Carbon::parse($input['assign_date'])->translatedFormat('jS M,Y');
        if (! empty($birthDate) && $assign_date < $birthDate) {
            return $this->sendResponse($input, __('messages.bed_assign.assign_date_should_not_be_smaller_than_patient_birth_date'));
        }
        $this->bedAssignRepository->update($input, $bedAssign);

        return $this->sendSuccess(__('messages.bed_assign.bed_assign').' '.__('messages.common.updated_successfully'));
    }

    /**
     * @throws \Exception
     */
    public function delete($id): \Illuminate\Http\JsonResponse
    {
        $bedAssign = BedAssign::where('id', $id)->first();
        if (! $bedAssign) {
            return $this->sendError(__('messages.bed_assign.bed_assign').' '.__('messages.common.not_found'));
        }
        $bedAssign->bed->update(['is_available' => 1]);
        $this->bedAssignRepository->delete($bedAssign->id);

        return $this->sendSuccess(__('messages.bed_assign.bed_assign').' '.__('messages.common.deleted_successfully'));
    }

    public function showBedStatus(): \Illuminate\Http\JsonResponse
    {
        $data['bedTypes'] = BedType::with([
            'beds.bedAssigns.patient.patientUser', 'beds.patientAdmission.patient.patientUser',
        ])->get();

        $bed_type = [];
        foreach ($data['bedTypes'] as $bed_types) {
            $bed_type[] = $bed_types->prepareData();
        }

        return $this->sendResponse($bed_type, 'Bed Status Retrieved Successfully');
    }

    public function showBedStatusDetail($id): \Illuminate\Http\JsonResponse
    {
        $data = Bed::where('id', $id)->with([
            'patientAdmission.patient.patientUser', 'bedAssigns.patient.patientUser',
        ])->first();

        if (! $data) {
            return $this->sendError(__('messages.patient.patient_details').' '.__('messages.common.not_found'));
        }

        if ($data->bedAssigns->isNotEmpty()) {
            return $this->sendResponse($data->prepareBedAssignData(), 'Bed status detail retrieved successfully');
        } elseif ($data->patientAdmission->count() != 0) {
            return $this->sendResponse($data->preparePatientAdmissionData(),
                'Bed status detail retrieved successfully');
        } else {
            $data = null;

            return $this->sendResponse($data, 'Bed status retrieved successfully');
        }
    }
}
