<?php

namespace App\Http\Controllers;

use App\Exports\DoctorExport;
use App\Http\Requests\CreateDoctorRequest;
use App\Http\Requests\UpdateDoctorRequest;
use App\Models\Appointment;
use App\Models\BirthReport;
use App\Models\DeathReport;
use App\Models\Doctor;
use App\Models\EmployeePayroll;
use App\Models\InvestigationReport;
use App\Models\IpdPatientDepartment;
use App\Models\OperationReport;
use App\Models\PatientAdmission;
use App\Models\PatientCase;
use App\Models\Prescription;
use App\Models\ScheduleDay;
use App\Repositories\DoctorRepository;
use Flash;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;

class DoctorController extends AppBaseController
{
    /** @var DoctorRepository */
    private $doctorRepository;

    public function __construct(DoctorRepository $doctorRepo)
    {
        $this->doctorRepository = $doctorRepo;
    }

    public function index()
    {
        $data['statusArr'] = Doctor::STATUS_ARR;

        return view('doctors.index', $data);
    }

    public function create()
    {
        $doctorsDepartments = getDoctorsDepartments();
        $bloodGroup = getBloodGroups();

        return view('doctors.create', compact('doctorsDepartments', 'bloodGroup'));
    }

    public function store(CreateDoctorRequest $request)
    {
        $input = $request->all();
        $input['status'] = isset($input['status']) ? 1 : 0;
        $doctor = $this->doctorRepository->store($input);

        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

        foreach ($days as $scheduleDay) {
            ScheduleDay::create([
                'doctor_id' => Session::get('doctor_id'),
                'schedule_id' => Session::get('schedule_id'),
                'available_on' => $scheduleDay,
                'available_from' => '10:00:00',
                'available_to' => '19:30:00',
            ]);
        }

        Flash::success(__('messages.case.doctor') . ' ' . __('messages.common.saved_successfully'));

        return redirect(route('doctors.index'));
    }

    public function show($doctorId)
    {
        if (! getLoggedInUser()->hasRole('Receptionist') && checkRecordAccess($doctorId) && ! getLoggedInUser()->hasRole('Nurse')) {
            $doctor = Doctor::find($doctorId);

            if (! $doctor) {
                return view('errors.404');
            }

            return view('employees.doctors.show')->with('doctor', $doctor);
        } else {
            $data['doctorData'] = Doctor::find($doctorId);
            $data['appointments'] = $data['doctorData']->appointments;

            if (! $data) {
                return view('errors.404');
            }

            return view('doctors.show')->with($data);
        }
    }

    public function edit(Doctor $doctor)
    {
        $user = $doctor->doctorUser;
        $doctorsDepartments = getDoctorsDepartments();
        $bloodGroup = getBloodGroups();

        return view('doctors.edit', compact('doctor', 'user', 'doctorsDepartments', 'bloodGroup'));
    }

    public function update(Doctor $doctor, UpdateDoctorRequest $request)
    {
        if ($doctor->is_default == 1) {
            Flash::error(_('messages.common.this_action_is_not_allowed_for_default_record'));

            return redirect(route('doctors.index'));
        }

        if (empty($doctor)) {
            Flash::error(__('messages.case.doctor') . ' ' . __('messages.common.not_found'));

            return redirect(route('doctors.index'));
        }

        $input = $request->all();
        $input['status'] = isset($input['status']) ? 1 : 0;
        $doctor = $this->doctorRepository->update($doctor, $input);
        Flash::success(__('messages.case.doctor') . ' ' . __('messages.common.saved_successfully'));

        return redirect(route('doctors.index'));
    }

    public function destroy(Doctor $doctor)
    {
        if ($doctor->is_default == 1) {
            Flash::error(_('messages.common.this_action_is_not_allowed_for_default_record'));

            return redirect(route('doctors.index'));
        }

        $doctorModels = [
            PatientCase::class,
            PatientAdmission::class,
            Appointment::class,
            BirthReport::class,
            DeathReport::class,
            InvestigationReport::class,
            OperationReport::class,
            Prescription::class,
            IpdPatientDepartment::class,
        ];

        $result = canDelete($doctorModels, 'doctor_id', $doctor->id);
        $empPayRollResult = canDeletePayroll(
            EmployeePayroll::class,
            'owner_id',
            $doctor->id,
            $doctor->doctorUser->owner_type
        );

        if ($result || $empPayRollResult) {
            return $this->sendError(__('messages.case.doctor') . ' ' . __('messages.common.cant_be_deleted'));
        }

        $doctor->user()->delete();
        $doctor->address()->delete();
        $doctor->delete();

        return $this->sendSuccess(__('messages.case.doctor') . ' ' . __('messages.common.saved_successfully'));
    }

    public function activeDeactiveStatus($id)
    {
        $doctor = Doctor::find($id);
        $status = ! $doctor->doctorUser->status;
        $doctor->doctorUser()->update(['status' => $status]);

        return $this->sendSuccess(__('messages.common.status_updated_successfully'));
    }

    public function doctorExport()
    {
        $doctors = Doctor::with('user')->get();
        if (!$doctors) {
            Flash::error(__('messages.common.no_data_available'));
            return redirect(route('doctors.index'));
        }
        return Excel::download(new DoctorExport, 'doctors-' . time() . '.xlsx');
    }
}
