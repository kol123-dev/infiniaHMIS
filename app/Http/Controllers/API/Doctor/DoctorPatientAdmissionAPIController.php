<?php

namespace App\Http\Controllers\API\Doctor;

use App\Http\Controllers\AppBaseController;
use App\Models\PatientAdmission;

class DoctorPatientAdmissionAPIController extends AppBaseController
{
    public function index(): \Illuminate\Http\JsonResponse
    {
        $patient_admissions = PatientAdmission::whereHas('patient.patientUser')->whereHas('doctor.doctorUser')->with('patient.patientUser', 'doctor.doctorUser', 'package', 'insurance')->where('doctor_id', getLoggedInUser()->owner_id)->get();

        $data = [];
        foreach ($patient_admissions as $patient_admission) {
            $data[] = $patient_admission->preparePatientAdmissionData();
        }

        return $this->sendResponse($data, 'Patient Admission Retrieved Successfully');
    }

    public function show($id): \Illuminate\Http\JsonResponse
    {
        $patient_admissions = PatientAdmission::whereHas('patient.patientUser')->whereHas('doctor.doctorUser')->with('patient.patientUser', 'doctor.doctorUser', 'package', 'insurance')->where('id', $id)->where('doctor_id', getLoggedInUser()->owner_id)->first();

        if (! $patient_admissions) {
            return $this->sendError(__('messages.patient_admission.patient_admission').' '.__('messages.common.not_found'));
        }

        return $this->sendResponse($patient_admissions->prepareDataForDetail(), 'Patient Admission Retrieved Successfully');
    }

    public function delete($id): \Illuminate\Http\JsonResponse
    {
        $patient_admissions = PatientAdmission::where('id', $id)->first();

        if (! $patient_admissions) {
            return $this->sendError(__('messages.patient_admission.patient_admission').' '.__('messages.common.not_found'));
        }

        $patient_admissions->delete();

        return $this->sendSuccess(__('messages.document.document').' '.__('messages.common.deleted_successfully'));
    }
}
