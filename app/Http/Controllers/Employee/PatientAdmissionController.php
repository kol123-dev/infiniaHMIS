<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\AppBaseController;
use App\Models\PatientAdmission;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PatientAdmissionController extends AppBaseController
{
    public function index()
    {
        $data['statusArr'] = PatientAdmission::STATUS_ARR;

        return view('employees.patient_admissions.index', $data);
    }

    public function show(PatientAdmission $patientAdmission)
    {
        if (checkRecordAccess($patientAdmission->patient_id)) {
            return view('errors.404');
        } else {
            return view('employees.patient_admissions.show')->with('patientAdmission', $patientAdmission);
        }
    }
}
