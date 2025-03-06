<?php

namespace App\Http\Controllers;

use App\Exports\AppointmentExport;
use App\Http\Requests\CreateAppointmentRequest;
use App\Http\Requests\UpdateAppointmentRequest;
use App\Models\AddCustomFields;
use App\Models\Appointment;
use App\Repositories\AppointmentRepository;
use App\Repositories\AppointmentTransactionRepository;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Flash;

class AppointmentController extends AppBaseController
{
    /** @var AppointmentRepository */
    private $appointmentRepository;

    /** @var AppointmentTransactionRepository */
    private $appointmentTransactionRepository;

    public function __construct(AppointmentRepository $appointmentRepo, AppointmentTransactionRepository $appointmentTransactionRepo)
    {
        $this->appointmentRepository = $appointmentRepo;
        $this->appointmentTransactionRepository = $appointmentTransactionRepo;
    }

    public function index()
    {
        $statusArr = Appointment::STATUS_ARR;

        return view('appointments.index', compact('statusArr'));
    }

    public function create()
    {
        $patients = $this->appointmentRepository->getPatients();
        $departments = $this->appointmentRepository->getDoctorDepartments();
        $statusArr = Appointment::STATUS_PENDING;
        $customField = AddCustomFields::where('module_name', AddCustomFields::Appointment)->get()->toArray();

        return view('appointments.create', compact('patients', 'departments', 'statusArr', 'customField'));
    }

    public function setFlutterWaveCredential()
    {
        $flutterwavePublicKey = getPaymentCredentials('flutterwave_public_key');
        $flutterwaveSecretKey = getPaymentCredentials('flutterwave_secret_key');

        if (!$flutterwavePublicKey && !$flutterwaveSecretKey) {
            return $this->sendError(__('messages.flutterwave.set_flutterwave_credential'));
        }

        config([
            'flutterwave.publicKey' => $flutterwavePublicKey,
            'flutterwave.secretKey' => $flutterwaveSecretKey,
        ]);
    }

    public function store(CreateAppointmentRequest $request)
    {
        $input = $request->all();

        $input['opd_date'] = $input['opd_date'] . $input['time'];
        $input['is_completed'] = isset($input['status']) ? Appointment::STATUS_COMPLETED : Appointment::STATUS_PENDING;
        $input['payment_type'] = $input['payment_mode'] ?? 1;

        if ($request->user()->hasRole('Patient')) {
            $input['patient_id'] = $request->user()->owner_id;
        }

        $jsonFields = [];

        foreach ($input as $key => $value) {
            if (strpos($key, 'field') === 0) {
                $jsonFields[$key] = $value;
            }
        }
        $input['custom_field'] = !empty($jsonFields) ? $jsonFields : null;

        if ($input['payment_mode'] != 8 && $input['payment_mode'] != 7 && $input['payment_mode'] != 9) {
            $data = $this->appointmentRepository->create($input);
        }

        $this->appointmentRepository->createNotification($input);
        if ($input['payment_mode'] == 3 || $input['payment_mode'] == 4 || $input['payment_mode'] == 5) {
            $data->update(['payment_type' => 1]);
        }
        $data['appointment_charge'] = $input['appointment_charge'];
        if ($input['payment_mode'] == 3) {

            $result = $this->appointmentTransactionRepository->stripeSession($data);

            return $this->sendResponse([
                'appointment_id' => $data->id,
                'payment_type' => $input['payment_mode'],
                $result,
            ], 'Stripe session created successfully');
        } elseif ($input['payment_mode'] == 4) {

            return $this->sendResponse([
                'appointment_id' => $data->id,
                'payment_type' => $input['payment_mode'],
                'amount' => $data->appointment_charge,
            ], 'Razorpay session created successfully');
        } elseif ($input['payment_mode'] == 5) {

            return $this->sendResponse([
                'appointment_id' => $data->id,
                'payment_type' => $input['payment_mode'],
                'amount' => $data->appointment_charge,
            ], 'paypal session created successfully');
        } elseif ($input['payment_mode'] == 8) {

            if (!in_array(strtoupper(getCurrentCurrency()), getFlutterWaveSupportedCurrencies())) {
                return $this->sendError(__('messages.payment.flutterwave_not_support'));
            }

            $this->setFlutterWaveCredential();
            $input['is_completed'] = 1;
            $url = $this->appointmentTransactionRepository->flutterWavePayment($input);

            return $this->sendResponse(['url' => $url, 'payment_type' => $input['payment_mode']], 'FlutterWave created successfully');
        } elseif ($input['payment_mode'] == 7) {

            if (strtoupper(getCurrentCurrency()) != 'INR') {
                return $this->sendError(__('messages.payment.phonepe_support_inr'));
            }
            $input['is_completed'] = 1;
            $url = $this->appointmentTransactionRepository->phonepePayment($input);

            return $this->sendResponse(['url' => $url, 'payment_type' => $input['payment_mode']], 'phonepe session created successfully.');
        } elseif ($input['payment_mode'] == 9) {
            $input['is_completed'] = 1;
            return $this->sendResponse(['payStackData' => $input], 'paystack session created successfully.');
        } else {
            $data = $this->appointmentTransactionRepository->store($data);

            return $this->sendSuccess(__('messages.web_menu.appointment') . ' ' . __('messages.common.saved_successfully'));
        }

        return $this->sendSuccess(__('messages.web_menu.appointment') . ' ' . __('messages.common.saved_successfully'));
    }

    public function show(Appointment $appointment)
    {
        return view('appointments.show')->with('appointment', $appointment);
    }

    public function edit(Appointment $appointment)
    {
        $patients = $this->appointmentRepository->getPatients();
        $doctors = $this->appointmentRepository->getDoctors($appointment->department_id);
        $departments = $this->appointmentRepository->getDoctorDepartments();
        $doctorCharge = $this->appointmentRepository->getDoctorsAppointmentCharge($appointment->doctor_id);
        $statusArr = $appointment->is_completed;
        $customField = AddCustomFields::where('module_name', AddCustomFields::Appointment)->get()->toArray();

        return view('appointments.edit', compact('appointment', 'patients', 'doctors', 'departments', 'statusArr', 'customField', 'doctorCharge'));
    }

    public function update(Appointment $appointment, UpdateAppointmentRequest $request)
    {
        $input = $request->all();
        $input['opd_date'] = $input['opd_date'] . $input['time'];
        $input['is_completed'] = isset($input['status']) ? $input['status'] : Appointment::STATUS_PENDING;
        $input['payment_type'] = $input['payment_mode'] ?? 4;

        if ($request->user()->hasRole('Patient')) {
            $input['patient_id'] = $request->user()->owner_id;
        }
        $jsonFields = [];

        foreach ($input as $key => $value) {
            if (strpos($key, 'field') === 0) {
                $jsonFields[$key] = $value;
            }
        }
        $input['custom_field'] = !empty($jsonFields) ? $jsonFields : null;

        $appointment = $this->appointmentRepository->update($input, $appointment->id);

        return $this->sendSuccess(__('messages.web_menu.appointment') . ' ' . __('messages.common.updated_successfully'));
    }

    public function destroy(Appointment $appointment)
    {
        return $this->sendError(__('messages.appointment.you_can_not_delete'));

        if (getLoggedinPatient() && $appointment->patient_id != getLoggedInUser()->owner_id) {
            return $this->sendError(__('messages.web_menu.appointment') . ' ' . __('messages.common.not_found'));
        } else {
            $this->appointmentRepository->delete($appointment->id);

            return $this->sendSuccess(__('messages.web_menu.appointment') . ' ' . __('messages.common.deleted_successfully'));
        }
    }

    public function getDoctors(Request $request)
    {
        $doctors = $this->appointmentRepository->getDoctors($request->id);

        return $this->sendResponse($doctors, 'Doctors Retrieved successfully');
    }

    public function getDoctorsCharge(Request $request)
    {
        $doctorCharge = $this->appointmentRepository->getDoctorsAppointmentCharge($request->id);

        return $this->sendResponse($doctorCharge, 'Doctors Change Retrieved successfully');
    }

    public function getBookingSlot(Request $request)
    {
        $inputs = $request->all();
        $data = $this->appointmentRepository->getBookingSlot($inputs);

        return $this->sendResponse($data, 'Booking slots Retrieved successfully');
    }

    public function appointmentExport()
    {
        $appointments = Appointment::with(['patient', 'doctor', 'department']);
        if (getLoggedInUser()->hasRole('Doctor')) {
            $appointments->where('doctor_id', getLoggedInUser()->owner_id)->get();
        }

        if (getLoggedInUser()->hasRole('Patient')) {
            $appointments->where('patient_id', getLoggedInUser()->owner_id)->get();
        }

        if ($appointments->count() == 0) {
            Flash::error(__('messages.common.no_data_available'));
            return redirect(route('appointments.index'));
        }

        return Excel::download(new AppointmentExport, 'appointments-' . time() . '.xlsx');
    }

    public function status(Appointment $appointment)
    {
        if (getLoggedinDoctor() && $appointment->doctor_id != getLoggedInUser()->owner_id) {
            return $this->sendError(__('messages.web_menu.appointment') . ' ' . __('messages.common.not_found'));
        } else {
            $isCompleted = !$appointment->is_completed;
            $appointment->update(['is_completed' => $isCompleted]);

            return $this->sendSuccess(__('messages.common.status_updated_successfully'));
        }
    }

    public function cancelAppointment(Appointment $appointment)
    {
        if ((getLoggedinPatient() && $appointment->patient_id != getLoggedInUser()->owner_id) || (getLoggedinDoctor() && $appointment->doctor_id != getLoggedInUser()->owner_id)) {
            return $this->sendError(__('messages.web_menu.appointment') . ' ' . __('messages.common.not_found'));
        } else {
            $appointment->update(['is_completed' => Appointment::STATUS_CANCELLED]);

            return $this->sendSuccess(__('messages.web_menu.appointment') . ' ' . __('messages.common.canceled'));
        }
    }
}
