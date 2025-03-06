<?php

namespace App\Http\Controllers;

use App;
use App\Exports\PatientExport;
use App\Http\Requests\CreatePatientRequest;
use App\Http\Requests\UpdatePatientRequest;
use App\Models\AddCustomFields;
use App\Models\AdvancedPayment;
use App\Models\Appointment;
use App\Models\BedAssign;
use App\Models\Bill;
use App\Models\BirthReport;
use App\Models\DeathReport;
use App\Models\InvestigationReport;
use App\Models\Invoice;
use App\Models\IpdPatientDepartment;
use App\Models\OperationReport;
use App\Models\Patient;
use App\Models\PatientAdmission;
use App\Models\PatientCase;
use App\Models\Prescription;
use App\Models\Vaccination;
use App\Repositories\AdvancedPaymentRepository;
use App\Repositories\PatientRepository;
use Flash;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class PatientController extends AppBaseController
{
    /** @var PatientRepository */
    private $patientRepository;

    public function __construct(PatientRepository $patientRepo)
    {
        $this->patientRepository = $patientRepo;
    }

    public function index()
    {
        $data['statusArr'] = Patient::STATUS_ARR;

        return view('patients.index', $data);
    }

    public function create()
    {
        $bloodGroup = getBloodGroups();
        $customField = AddCustomFields::where('module_name', AddCustomFields::Patient)->get()->toArray();

        return view('patients.create', compact('bloodGroup', 'customField'));
    }

    public function store(CreatePatientRequest $request)
    {
        $input = $request->all();

        $this->patientRepository->store($input);
        $this->patientRepository->createNotification($input);
        Flash::success(__('messages.advanced_payment.patient') . ' ' . __('messages.common.saved_successfully'));

        return redirect(route('patients.index'));
    }

    public function show($patientId)
    {
        $data = $this->patientRepository->getPatientAssociatedData($patientId);

        if (! $data) {
            return view('errors.404');
        }

        if (getLoggedinPatient() && checkRecordAccess($data->id)) {
            return view('errors.404');
        } else {
            $advancedPaymentRepo = App::make(AdvancedPaymentRepository::class);
            $patients = $advancedPaymentRepo->getPatients();
            $user = Auth::user();
            if ($user->hasRole('Doctor')) {
                $vaccinationPatients = getPatientsList($user->owner_id);
            } else {
                $vaccinationPatients = Patient::getActivePatientNames();
            }
            $vaccinations = Vaccination::toBase()->pluck('name', 'id')->toArray();
            natcasesort($vaccinations);

            return view('patients.show', compact('data', 'patients', 'vaccinations', 'vaccinationPatients'));
        }
    }

    public function edit(Patient $patient)
    {
        $bloodGroup = getBloodGroups();
        $customField = AddCustomFields::where('module_name', AddCustomFields::Patient)->get()->toArray();

        return view('patients.edit', compact('patient', 'bloodGroup', 'customField'));
    }

    public function update(Patient $patient, UpdatePatientRequest $request)
    {
        if ($patient->is_default == 1) {
            Flash::error(__('messages.common.this_action_is_not_allowed_for_default_record'));

            return redirect(route('patients.index'));
        }

        $input = $request->all();
        $input['status'] = isset($input['status']) ? 1 : 0;
        $this->patientRepository->update($input, $patient);

        Flash::success(__('messages.advanced_payment.patient') . ' ' . __('messages.common.updated_successfully'));

        return redirect(route('patients.index'));
    }

    public function destroy(Patient $patient)
    {
        if ($patient->is_default == 1) {
            return $this->sendError(__('messages.common.this_action_is_not_allowed_for_default_record'));
        }

        $patientModels = [
            BirthReport::class,
            DeathReport::class,
            InvestigationReport::class,
            OperationReport::class,
            Appointment::class,
            BedAssign::class,
            PatientAdmission::class,
            PatientCase::class,
            Bill::class,
            Invoice::class,
            AdvancedPayment::class,
            Prescription::class,
            IpdPatientDepartment::class,
        ];
        $result = canDelete($patientModels, 'patient_id', $patient->id);

        if ($result) {
            return $this->sendError(__('messages.advanced_payment.patient') . ' ' . __('messages.common.cant_be_deleted'));
        }

        $patient->patientUser()->delete();
        $patient->address()->delete();
        $patient->delete();

        return $this->sendSuccess(__('messages.advanced_payment.patient') . ' ' . __('messages.common.deleted_successfully'));
    }

    public function activeDeactiveStatus($id)
    {
        $patient = Patient::find($id);
        $status = ! $patient->patientUser->status;
        $patient->patientUser()->update(['status' => $status]);

        return $this->sendSuccess(__('messages.common.status_updated_successfully'));
    }

    public function patientExport()
    {
        $patients = Patient::all();
        if (!$patients) {
            Flash::error(__('messages.common.no_data_available'));
            return redirect(route('patients.index'));
        }
        return Excel::download(new PatientExport, 'patients-' . time() . '.xlsx');
    }

    public function getBirthDate($id)
    {
        return Patient::whereId($id)->with('user')->first();
    }
}
