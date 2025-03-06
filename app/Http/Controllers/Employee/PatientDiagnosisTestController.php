<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\AppBaseController;
use App\Models\PatientDiagnosisTest;
use App\Repositories\PatientDiagnosisTestRepository;
use \PDF;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PatientDiagnosisTestController extends AppBaseController
{
    /**
     * @var PatientDiagnosisTestRepository
     */
    private $patientDiagnosisTestRepository;

    public function __construct(
        PatientDiagnosisTestRepository $patientDiagnosisTestRepository
    ) {
        $this->patientDiagnosisTestRepository = $patientDiagnosisTestRepository;
    }

    public function index(Request $request)
    {
        return view('employees.patient_diagnosis_test.index');
    }

    public function show(PatientDiagnosisTest $patientDiagnosisTest)
    {
        if (checkRecordAccess($patientDiagnosisTest->patient_id)) {
            return view('errors.404');
        } else {
            $patientDiagnosisTests = $this->patientDiagnosisTestRepository->getPatientDiagnosisTestProperty($patientDiagnosisTest->id);

            return view('employees.patient_diagnosis_test.show', compact('patientDiagnosisTests', 'patientDiagnosisTest'));
        }
    }

    public function convertToPdf(PatientDiagnosisTest $patientDiagnosisTest)
    {
        $data = $this->patientDiagnosisTestRepository->getSettingList();
        $data['patientDiagnosisTest'] = $patientDiagnosisTest;
        $data['patientDiagnosisTests'] = $this->patientDiagnosisTestRepository->getPatientDiagnosisTestProperty($patientDiagnosisTest->id);

        $pdf = PDF::loadView('employees.patient_diagnosis_test.diagnosis_test_pdf', $data);

        return $pdf->stream($patientDiagnosisTest->patient->patientUser->full_name.'-'.$patientDiagnosisTest->report_number);
    }
}
