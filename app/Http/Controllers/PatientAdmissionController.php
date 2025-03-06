<?php

namespace App\Http\Controllers;

use App\Exports\PatientAdmissionExport;
use App\Http\Requests\CreatePatientAdmissionRequest;
use App\Http\Requests\UpdatePatientAdmissionRequest;
use App\Models\Bill;
use App\Models\Patient;
use App\Models\PatientAdmission;
use App\Repositories\PatientAdmissionRepository;
use Carbon\Carbon;
use Flash;
use Maatwebsite\Excel\Facades\Excel;

class PatientAdmissionController extends AppBaseController
{
    /** @var PatientAdmissionRepository */
    private $patientAdmissionRepository;

    public function __construct(PatientAdmissionRepository $patientAdmissionRepo)
    {
        $this->patientAdmissionRepository = $patientAdmissionRepo;
    }

    public function index()
    {
        $data['statusArr'] = PatientAdmission::STATUS_ARR;

        return view('patient_admissions.index', $data);
    }

    public function create()
    {
        $data = $this->patientAdmissionRepository->getSyncList();

        return view('patient_admissions.create', compact('data'));
    }

    public function store(CreatePatientAdmissionRequest $request)
    {
        $input = $request->all();
        $input['status'] = isset($input['status']) ? 1 : 0;
        $patientId = Patient::with('patientUser')->whereId($input['patient_id'])->first();
        $birthDate = $patientId->patientUser->dob;
        $admissionDate = Carbon::parse($input['admission_date'])->toDateString();
        if (! empty($birthDate) && $admissionDate < $birthDate) {
            Flash::error(__('messages.patient_admission.admission_date_should_not_be_smaller_than_patient_birth_date'));

            return redirect()->back()->withInput($input);
        }

        $this->patientAdmissionRepository->store($input);

        Flash::success(__('messages.patient_admission.patient_admission') . ' ' . __('messages.common.saved_successfully'));

        return redirect(route('patient-admissions.index'));
    }

    public function show(PatientAdmission $patientAdmission)
    {
        return view('patient_admissions.show')->with('patientAdmission', $patientAdmission);
    }

    public function edit(PatientAdmission $patientAdmission)
    {
        if (getLoggedinDoctor()) {
            if (getLoggedInUser()->owner_id == $patientAdmission->doctor_id) {
                $data = $this->patientAdmissionRepository->getSyncList($patientAdmission);
                $data['patientAdmissionDate'] = PatientAdmission::whereId($patientAdmission->id)->with(
                    'patient',
                    function ($q) {
                        $q->with('patientUser');
                    }
                )->first();

                return view('patient_admissions.edit', compact('data', 'patientAdmission'));
            } else {
                return view('errors.404');
            }
        }
        $data = $this->patientAdmissionRepository->getSyncList($patientAdmission);
        $data['patientAdmissionDate'] = PatientAdmission::whereId($patientAdmission->id)->with(
            'patient',
            function ($q) {
                $q->with('patientUser');
            }
        )->first();

        return view('patient_admissions.edit', compact('data', 'patientAdmission'));
    }

    public function update(PatientAdmission $patientAdmission, UpdatePatientAdmissionRequest $request)
    {
        $input = $request->all();
        $input['status'] = isset($input['status']) ? 1 : 0;
        $patientId = Patient::with('patientUser')->whereId($patientAdmission->patient_id)->first();
        $birthDate = $patientId->patientUser->dob;
        $admissionDate = Carbon::parse($input['admission_date'])->toDateString();

        if (! empty($birthDate) && $admissionDate < $birthDate) {
            Flash::error(__('messages.patient_admission.admission_date_should_not_be_smaller_than_patient_birth_date'));

            return redirect()->back()->withInput($input);
        }

        $this->patientAdmissionRepository->update($input, $patientAdmission);

        Flash::success(__('messages.patient_admission.patient_admission') . ' ' . __('messages.common.updated_successfully'));

        return redirect(route('patient-admissions.index'));
    }

    public function destroy(PatientAdmission $patientAdmission)
    {
        if (getLoggedinDoctor() && checkRecordAccess($patientAdmission->doctor_id)) {
            return $this->sendError(__('messages.patient_admission.patient_admission') . ' ' . __('messages.common.not_found'));
        } else {
            $patientAdmissionModel = [
                Bill::class,
            ];
            $result = canDelete(
                $patientAdmissionModel,
                'patient_admission_id',
                $patientAdmission->patient_admission_id
            );
            if ($result) {
                return $this->sendError(__('messages.patient_admission.patient_admission') . ' ' . __('messages.common.cant_be_deleted'));
            }

            if (! empty($patientAdmission->bed_id)) {
                $this->patientAdmissionRepository->setBedAvailable($patientAdmission->bed_id);
            }
            $this->patientAdmissionRepository->delete($patientAdmission->id);

            return $this->sendSuccess(__('messages.patient_admission.patient_admission') . ' ' . __('messages.common.deleted_successfully'));
        }
    }

    public function activeDeactiveStatus($id)
    {
        $patientAdmission = PatientAdmission::find($id);

        if (! (getLoggedInUser()->hasRole('Receptionist') || getLoggedInUser()->hasRole('Case Manager')) && checkRecordAccess($patientAdmission->doctor_id)) {
            return $this->sendError(__('messages.patient_admission.patient_admission') . ' ' . __('messages.common.not_found'));
        } else {
            $status = ! $patientAdmission->status;
            $patientAdmission->update(['status' => $status]);

            return $this->sendSuccess(__('messages.common.status_updated_successfully'));
        }
    }

    public function patientAdmissionExport()
    {
        $patientAdmissions = PatientAdmission::with('patient.patientUser', 'doctor.doctorUser', 'package', 'insurance');
        if (getLoggedInUser()->hasRole('Doctor')) {
            $patientAdmissions->where('doctor_id', getLoggedInUser()->owner_id);
        }
        $patientAdmissions = $patientAdmissions->get();
        if ($patientAdmissions->isEmpty()) {
            Flash::error(__('messages.common.no_data_available'));
            return redirect(route('patient-admissions.index'));
        }
        return Excel::download(new PatientAdmissionExport, 'patient-admissions-' . time() . '.xlsx');
    }

    public function showModal(PatientAdmission $patientAdmission)
    {
        if (! (getLoggedInUser()->hasRole('Receptionist') || getLoggedInUser()->hasRole('Case Manager')) && checkRecordAccess($patientAdmission->doctor_id)) {
            return $this->sendError(__('messages.patient_admission.patient_admission') . ' ' . __('messages.common.not_found'));
        } else {
            $patientAdmission->load(['patient.patientUser', 'doctor.doctorUser', 'package', 'insurance', 'bed']);

            return $this->sendResponse($patientAdmission, 'Patient Admission Retrieved Successfully');
        }
    }
}
