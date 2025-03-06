<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Models\Doctor;
use App\Models\Prescription;
use App\Repositories\PrescriptionRepository;
use Illuminate\Http\JsonResponse;

class PrescriptionAPIController extends AppBaseController
{
    /**
     * @var PrescriptionRepository
     */
    private $prescriptionRepository;

    public function __construct(PrescriptionRepository $prescriptionRepo)
    {
        $this->prescriptionRepository = $prescriptionRepo;
    }

    public function index(): JsonResponse
    {
        $prescriptions = Prescription::with('patient.patientUser', 'doctor.doctorUser')->where('patient_id',
            getLoggedInUser()->owner_id)->orderBy('id', 'desc')->get();
        $data = [];
        foreach ($prescriptions as $prescription) {
            /** @var Prescription $prescription */
            $data[] = $prescription->preparePrescription();
        }

        return $this->sendResponse($data, 'Prescriptions Retrieved Successfully');
    }

    public function show($id): JsonResponse
    {
        $prescription = Prescription::with('patient.patientUser', 'doctor.doctorUser')->where('id',
            $id)->where('patient_id', getLoggedInUser()->owner_id)->first();

        if (! $prescription) {
            return $this->sendError(__('messages.medicine_bills.prescription_not_found'));
        }

        return $this->sendResponse($prescription->preparePrescription(), 'Prescription Retrieved Successfully');
    }

    public function DoctorPrescriptionList(): JsonResponse
    {
        $doctor = Doctor::where('user_id', getLoggedInUserId())->first();
        $prescriptions = Prescription::with('patient', 'doctor')->where('doctor_id', $doctor->id)->orderBy('id', 'desc')->get();
        $data = [];
        foreach ($prescriptions as $prescription) {
            $data[] = $prescription->prepareDoctorPrescription();
        }

        return $this->sendResponse($data, 'Prescription Retrieved Successfully');
    }

    public function prescriptionShow($id): JsonResponse
    {
        if (getLoggedinDoctor()) {
            $prescription = Prescription::with('patient', 'doctor')->where('id', $id)->where('doctor_id',
                getLoggedInUser()->owner_id)->first();
            if (! $prescription) {
                return $this->sendError(__('messages.medicine_bills.prescription_not_found'));
            }

            /** @var Prescription $prescription */

            return $this->sendResponse($prescription->prepareDoctorPrescriptionDetailData(),
                'Prescription Detail Retrieved Successfully');
        }

        if (getLoggedinPatient()) {
            $prescription = Prescription::with('patient', 'doctor')->where('id', $id)->where('patient_id',
                getLoggedInUser()->owner_id)->first();

            if (! $prescription) {
                return $this->sendError(__('messages.medicine_bills.prescription_not_found'));
            }

            /** @var Prescription $prescription */

            return $this->sendResponse($prescription->preparePatientPrescriptionDetailData(),
                'Prescription Detail Retrieved Successfully');
        }
    }

    public function destroy($id): JsonResponse
    {
        $prescription = $this->prescriptionRepository->find($id);
        if (empty($prescription)) {
            return $this->sendError(__('messages.medicine_bills.prescription_not_found'));
        }
        if ($prescription->doctor_id == getLoggedInUser()->owner_id) {
            $prescription->delete();

            return $this->sendSuccess(__('messages.prescription.prescription').' '.__('messages.common.deleted_successfully'));
        } else {
            return $this->sendError(__('messages.prescription.prescription').' '.__('messages.common.not_found'));
        }
    }
}
