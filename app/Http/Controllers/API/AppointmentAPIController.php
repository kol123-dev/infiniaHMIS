<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\CreateAppointmentRequest;
use App\Models\Appointment;
use App\Repositories\AppointmentRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AppointmentAPIController extends AppBaseController
{
    /** @var AppointmentRepository */
    private $appointmentRepository;

    public function __construct(AppointmentRepository $appointmentRepo)
    {
        $this->appointmentRepository = $appointmentRepo;
    }

    public function index(): JsonResponse
    {
        $appointments = Appointment::with('patient.patientUser', 'doctor.doctorUser', 'department')->where('patient_id', getLoggedInUser()->patient->id)->orderBy('id', 'desc')->get();

        $data = [];
        foreach ($appointments as $appointment) {
            $data[] = $appointment->prepareAppointment();
        }

        return $this->sendResponse($data, 'Appointments Retrieved Successfully');
    }

    public function filter(Request $request): JsonResponse
    {
        $status = $request->get('status');
        $appointments = $this->appointmentRepository->filter($status);

        $data = [];
        foreach ($appointments as $appointment) {
            $data[] = $appointment->prepareAppointment();
        }

        return $this->sendResponse($data, 'Appointments Retrieved Successfully');
    }

    public function getDoctorDepartment(): JsonResponse
    {
        $doctor_departments = $this->appointmentRepository->getDoctorDepartmentForAPI();

        return $this->sendResponse($doctor_departments, 'Doctor department Retrieved Successfully');
    }

    public function getDoctors($id): JsonResponse
    {
        $doctor = $this->appointmentRepository->getDepartmentDoctorList($id);

        return $this->sendResponse($doctor, 'Doctor Retrieved Successfully');
    }

    public function bookingSlots(Request $request): JsonResponse
    {
        $inputs = $request->all();
        $data = $this->appointmentRepository->getBookingSlotAPI($inputs);

        return $this->sendResponse($data, 'Retrieved successfully');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateAppointmentRequest $request): JsonResponse
    {
        $input = $request->all();
        $input['opd_date'] = $input['opd_date'].' '.$input['time'];
        if (Appointment::where('opd_date', $input['opd_date'])->first()) {
            return $this->sendError(__('messages.appointment.please_select_appointment_time_slot'));
        }
        $input['patient_id'] = auth()->user()->owner_id;
        $input['is_completed'] = isset($input['status']) ? Appointment::STATUS_COMPLETED : Appointment::STATUS_PENDING;
        $success = $this->appointmentRepository->create($input);
        if ($success) {
            return $this->sendSuccess(__('messages.appointments').' '.__('messages.common.saved_successfully'));
        } else {
            return $this->sendError(__('messages.common.something_wen_wrong'));
        }
    }

    public function cancelAppointment(Request $request): JsonResponse
    {
        $appointment = Appointment::where('id', $request->id)->first();

        if (! $appointment) {
            return $this->sendError(__('messages.web_menu.appointment').' '.__('messages.common.not_found'));
        }

        $appointment->update(['is_completed' => Appointment::STATUS_CANCELLED]);

        return $this->sendSuccess(__('messages.web_menu.appointment').' '.__('messages.common.cancelled_successfully'));
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return void
     */
    public function edit(int $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     *
     * @throws \Exception
     */
    public function destroy(Request $request): JsonResponse
    {
        $appointment = Appointment::where('id', $request->id)->first();

        if (! $appointment) {
            return $this->sendError(__('messages.web_menu.appointment').' '.__('messages.common.not_found'));
        }

        $appointment->delete($request->id);

        return $this->sendSuccess(__('messages.web_menu.appointment').' '.__('messages.common.deleted_successfully'));
    }
}
