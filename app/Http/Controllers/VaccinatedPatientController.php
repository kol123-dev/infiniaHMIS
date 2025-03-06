<?php

namespace App\Http\Controllers;

use App\Exports\VaccinatedPatientExport;
use App\Http\Requests\CreateVaccinatedPatientRequest;
use App\Http\Requests\UpdateVaccinatedPatientRequest;
use App\Models\VaccinatedPatients;
use App\Repositories\VaccinatedPatientRepository;
use Exception;
use Maatwebsite\Excel\Facades\Excel;
use Flash;

class VaccinatedPatientController extends AppBaseController
{
    /**
     * @var VaccinatedPatientRepository
     */
    private $vaccinatedPatientRepository;

    public function __construct(VaccinatedPatientRepository $vaccinatedPatientRepository)
    {
        $this->vaccinatedPatientRepository = $vaccinatedPatientRepository;
    }

    public function index()
    {
        $data = $this->vaccinatedPatientRepository->getVaccinatedPatientData();

        return view('vaccinated_patients.index')->with($data);
    }

    public function store(CreateVaccinatedPatientRequest $request)
    {
        try {
            $input = $request->all();
            $checkValidation = checkVaccinatePatientValidation($input, null, null);

            if ($checkValidation) {
                return $this->sendError(__('messages.vaccinated_patient.already_registered_dose'));
            }

            $this->vaccinatedPatientRepository->create($input);

            return $this->sendSuccess(__('messages.vaccinated_patient.vaccinate_patient') . ' ' . __('messages.common.saved_successfully'));
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function edit(VaccinatedPatients $vaccinatedPatient)
    {
        return $this->sendResponse($vaccinatedPatient, 'Vaccinated Patients retrieved successfully.');
    }

    public function update(UpdateVaccinatedPatientRequest $request, VaccinatedPatients $vaccinatedPatient)
    {
        try {
            $input = $request->all();

            if (
                $input['patient_id'] == $vaccinatedPatient->patient_id &&
                $input['vaccination_id'] == $vaccinatedPatient->vaccination_id &&
                $input['dose_number'] == $vaccinatedPatient->dose_number
            ) {
            } else {
                $checkValidation = checkVaccinatePatientValidation($input, $vaccinatedPatient, $isCreate = true);

                if ($checkValidation) {
                    return $this->sendError(__('messages.vaccinated_patient.already_registered_dose'));
                }
            }
            $this->vaccinatedPatientRepository->update($input, $vaccinatedPatient->id);

            return $this->sendSuccess(__('messages.vaccinated_patient.vaccinate_patient') . ' ' . __('messages.common.updated_successfully'));
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function destroy(VaccinatedPatients $vaccinatedPatient)
    {
        try {
            $vaccinatedPatient->delete();

            return $this->sendSuccess(__('messages.vaccinated_patient.vaccinate_patient') . ' ' . __('messages.common.deleted_successfully'));
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function vaccinatedPatientExport()
    {
        $vaccinatedPatients = VaccinatedPatients::with('patient.user', 'vaccination')->get();
        if (!$vaccinatedPatients) {
            Flash::error(__('messages.common.no_data_available'));
            return redirect(route('vaccinated-patients.index'));
        }
        return Excel::download(new VaccinatedPatientExport, 'vaccinated_patient-' . time() . '.xlsx');
    }
}
