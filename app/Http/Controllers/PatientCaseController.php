<?php

namespace App\Http\Controllers;

use App\Exports\PatientCaseExport;
use App\Http\Requests\CreatePatientCaseRequest;
use App\Http\Requests\UpdatePatientCaseRequest;
use App\Models\BedAssign;
use App\Models\BirthReport;
use App\Models\DeathReport;
use App\Models\IpdPatientDepartment;
use App\Models\OperationReport;
use App\Models\Patient;
use App\Models\PatientCase;
use App\Repositories\PatientCaseRepository;
use Carbon\Carbon;
use Flash;
use Maatwebsite\Excel\Facades\Excel;

class PatientCaseController extends AppBaseController
{
    /** @var PatientCaseRepository */
    private $patientCaseRepository;

    public function __construct(PatientCaseRepository $patientCaseManagerRepo)
    {
        $this->patientCaseRepository = $patientCaseManagerRepo;
    }

    public function index()
    {
        $data['statusArr'] = PatientCase::STATUS_ARR;

        return view('patient_cases.index', $data);
    }

    public function create()
    {
        $patients = $this->patientCaseRepository->getPatients();
        $doctors = $this->patientCaseRepository->getDoctors();

        return view('patient_cases.create', compact('patients', 'doctors'));
    }

    public function store(CreatePatientCaseRequest $request)
    {
        $input = $request->all();
        $patientId = Patient::with('patientUser')->whereId($input['patient_id'])->first();
        $birthDate = $patientId->patientUser->dob;
        $caseDate = Carbon::parse($input['date'])->toDateString();

        if (! empty($birthDate) && $caseDate < $birthDate) {
            Flash::error(__('messages.case.case_date_should_not_be_smaller_than_patient_birth_date'));

            return redirect()->back()->withInput($input);
        }

        $input['fee'] = removeCommaFromNumbers($input['fee']);
        $input['status'] = isset($input['status']) ? 1 : 0;
        $input['phone'] = preparePhoneNumber($input, 'phone');

        $this->patientCaseRepository->store($input);
        $this->patientCaseRepository->createNotification($input);

        Flash::success(__('messages.case.case') . ' ' . __('messages.common.saved_successfully'));

        return redirect(route('patient-cases.index'));
    }

    public function show(PatientCase $patientCase)
    {
        return view('patient_cases.show')->with('patientCase', $patientCase);
    }

    public function edit(PatientCase $patientCase)
    {
        $patients = $this->patientCaseRepository->getPatients();
        $doctors = $this->patientCaseRepository->getDoctors();

        return view('patient_cases.edit', compact('patientCase', 'patients', 'doctors'));
    }

    public function update(PatientCase $patientCase, UpdatePatientCaseRequest $request)
    {
        $input = $request->all();
        $patientId = Patient::with('patientUser')->whereId($input['patient_id'])->first();
        $birthDate = $patientId->patientUser->dob;
        $caseDate = Carbon::parse($input['date'])->toDateString();
        if (! empty($birthDate) && $caseDate < $birthDate) {
            Flash::error(__('messages.case.case_date_should_not_be_smaller_than_patient_birth_date'));

            return redirect()->back()->withInput($input);
        }
        $input['fee'] = removeCommaFromNumbers($input['fee']);
        $input['status'] = isset($input['status']) ? 1 : 0;
        $input['phone'] = preparePhoneNumber($input, 'phone');

        $patientCase = $this->patientCaseRepository->update($input, $patientCase->id);

        Flash::success(__('messages.case.case') . ' ' . __('messages.common.updated_successfully'));

        return redirect(route('patient-cases.index'));
    }

    public function destroy(PatientCase $patientCase)
    {
        $patientCaseModel = [
            BedAssign::class,
            BirthReport::class,
            DeathReport::class,
            OperationReport::class,
            IpdPatientDepartment::class,
        ];
        $result = canDelete($patientCaseModel, 'case_id', $patientCase->case_id);

        if ($result) {
            return $this->sendError(__('messages.case.case') . ' ' . __('messages.common.cant_be_deleted'));
        }

        $this->patientCaseRepository->delete($patientCase->id);

        return $this->sendSuccess(__('messages.case.case') . ' ' . __('messages.common.deleted_successfully'));
    }

    public function activeDeActiveStatus($id)
    {
        $patientCase = PatientCase::find($id);
        $patientCase->status = ! $patientCase->status;
        $patientCase->update(['status' => $patientCase->status]);

        return $this->sendSuccess(__('messages.common.status_updated_successfully'));
    }

    public function patientCaseExport()
    {
        $patientCases = PatientCase::with('patient.user', 'doctor.user')->get();
        if (!$patientCases) {
            Flash::error(__('messages.common.no_data_available'));
            return redirect(route('patient-cases.index'));
        }
        return Excel::download(new PatientCaseExport, 'patient-cases-' . time() . '.xlsx');
    }

    public function showModal(PatientCase $patientCase)
    {
        $patientCase->load(['patient.patientUser', 'doctor.doctorUser']);

        $currency = strtoupper(getCurrentCurrency());
        $patientCase = [
            'case_id' => $patientCase->case_id,
            'patient_name' => $patientCase->patient->patientUser->full_name,
            'doctor_name' => $patientCase->doctor->doctorUser->full_name,
            'phone' => $patientCase->phone,
            'date' => $patientCase->date,
            'fee' => checkNumberFormat($patientCase->fee, $currency),
            'status' => $patientCase->status,
            'created_at' => $patientCase->created_at,
            'updated_at' => $patientCase->updated_at,
            'description' => $patientCase->description,
        ];

        return $this->sendResponse($patientCase, 'Patient Case Retrieved Successfully.');
    }
}
