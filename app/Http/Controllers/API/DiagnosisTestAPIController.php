<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Models\PatientDiagnosisTest;
use App\Repositories\PatientDiagnosisTestRepository;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class DiagnosisTestAPIController extends AppBaseController
{
    /**
     * @var PatientDiagnosisTestRepository
     */
    private $patientDiagnosisTestRepository;

    public function __construct(PatientDiagnosisTestRepository $patientDiagnosisTestRepository)
    {
        $this->patientDiagnosisTestRepository = $patientDiagnosisTestRepository;
    }

    public function index(): JsonResponse
    {
        if (getLoggedinPatient() || getLoggedinDoctor()) {
            $diagnosis = PatientDiagnosisTest::query()->with('patient.patientUser', 'doctor.doctorUser');
            $user = Auth::user();
            if ($user->hasRole('Patient')) {
                $diagnosis->where('patient_id', $user->owner_id);
            }
            if ($user->hasRole('Doctor')) {
                $diagnosis->where('doctor_id', $user->owner_id);
            }
            $diagnosis = $diagnosis->orderBy('id', 'desc')->orderBy('id', 'desc')->get();

            $data = [];
            foreach ($diagnosis as $diagnose) {
                $data[] = $diagnose->prepareDiagnosis();
            }

            return $this->sendResponse($data, 'Diagnosis Retrieved Successfully');
        }
    }

    public function show($id): JsonResponse
    {
        if (getLoggedinPatient()) {
            $diagnosis = PatientDiagnosisTest::with('patient.patientUser', 'doctor.doctorUser')->where('id',
                $id)->where('patient_id', getLoggedInUser()->owner_id)->first();

            if (! $diagnosis) {
                return $this->sendError(__('messages.patient_diagnosis_test.diagnosis').' '.__('messages.common.not_found'));
            }

            return $this->sendResponse($diagnosis->prepareDiagnosisDetailForPatient(), 'Diagnosis Retrieved Successfully');
        }

        if (getLoggedinDoctor()) {
            $diagnosis = PatientDiagnosisTest::with('patient.patientUser', 'doctor.doctorUser')->where('id',
                $id)->Where('doctor_id', getLoggedInUser()->owner_id)->first();

            if (! $diagnosis) {
                return $this->sendError(__('messages.patient_diagnosis_test.diagnosis').' '.__('messages.common.not_found'));
            }

            return $this->sendResponse($diagnosis->prepareDiagnosisDetailForDoctor(), 'Diagnosis Retrieved Successfully');
        }
    }

    /**
     * @throws Exception
     */
    public function destroy($id): JsonResponse
    {
        $diagnosis_test = PatientDiagnosisTest::where('id', $id)->first();
        if (empty($diagnosis_test) || $diagnosis_test->doctor_id != getLoggedInUser()->owner_id) {
            return $this->sendError(__('messages.patient_diagnosis_test.diagnosis').' '.__('messages.common.not_found'));
        } else {
            $this->patientDiagnosisTestRepository->delete($id);

            return $this->sendSuccess(__('messages.patient_diagnosis_test.diagnosis').' '.__('messages.common.deleted_successfully'));
        }
    }
}
