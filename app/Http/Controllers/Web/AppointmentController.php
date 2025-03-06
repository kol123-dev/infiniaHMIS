<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\CreateWebAppointmentRequest;
use App\Models\Appointment;
use App\Models\Department;
use App\Models\Patient;
use App\Models\User;
use App\Repositories\AppointmentRepository;
use App\Repositories\AppointmentTransactionRepository;
use Arr;
use Carbon\Carbon;
use DB;
use Hash;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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

    public function setFlutterWaveCredential()
    {
        $flutterwavePublicKey = getPaymentCredentials('flutterwave_public_key');
        $flutterwaveSecretKey = getPaymentCredentials('flutterwave_secret_key');

        if(!$flutterwavePublicKey && !$flutterwaveSecretKey){
            return $this->sendError(__('messages.flutterwave.set_flutterwave_credential'));
        }

        config([
            'flutterwave.publicKey' => $flutterwavePublicKey,
            'flutterwave.secretKey' => $flutterwaveSecretKey,
        ]);
    }

    public function store(CreateWebAppointmentRequest $request)
    {

        $input = $request->all();
        $input['opd_date'] = $input['opd_date'].$input['time'];
        $input['status'] = true;
        $input['payment_type'] = $input['payment_mode'] ?? 4;
        try {
            DB::beginTransaction();
            if ($input['patient_type'] == 2 && ! empty($input['patient_type'])) {
                $jsonFields = [];
                foreach ($input as $key => $value) {
                    if (strpos($key, 'field') === 0) {
                        $jsonFields[$key] = $value;
                    }
                }

                $input['custom_field'] = !empty($jsonFields) ? $jsonFields : null;
                if($input['payment_mode'] != 7 && $input['payment_mode'] != 8 && $input['payment_mode'] != 9){
                    $appointment = $this->appointmentRepository->create($input);
                }

                if($input['payment_mode'] == 3 || $input['payment_mode'] == 4 || $input['payment_mode'] == 5){
                    $appointment->update(['patient_type',1]);
                }
                $appointment['appointment_charge'] = $input['appointment_charge'];
                if($input['payment_mode'] == 3){
                    DB::commit();
                    $result = $this->appointmentTransactionRepository->WebAppointmentstripeSession($appointment);

                    return $this->sendResponse([
                        'appointment_id' => $appointment->id,
                        'payment_type' => $input['payment_mode'],
                        $result
                    ],'Stripe session created successfully');

                } elseif($input['payment_mode'] == 4){
                    DB::commit();
                    return $this->sendResponse([
                        'appointment_id' => $appointment->id,
                        'payment_type' => $input['payment_mode'],
                        'amount' =>  $appointment->appointment_charge,
                    ],'Razorpay session created successfully');
                } elseif($input['payment_mode'] ==  5) {
                    DB::commit();
                    return $this->sendResponse([
                        'appointment_id' => $appointment->id,
                        'payment_type' => $input['payment_mode'],
                        'amount' =>  $appointment->appointment_charge,
                    ],'paypal session created successfully');

                } elseif($input['payment_mode'] ==  8) {
                    DB::commit();

                    if(!in_array(strtoupper(getCurrentCurrency()),getFlutterWaveSupportedCurrencies())){
                        return $this->sendError(__('messages.payment.flutterwave_not_support'));
                    }

                    $this->setFlutterWaveCredential();
                    $input['web_appointment'] = true;
                    $url = $this->appointmentTransactionRepository->flutterWavePayment($input);

                    return $this->sendResponse(['url' => $url,'payment_type' => $input['payment_mode']],'FlutterWave created successfully');

                } elseif($input['payment_mode'] ==  7) {
                    DB::commit();

                    if (strtoupper(getCurrentCurrency()) != 'INR') {
                        return $this->sendError(__('messages.payment.phonepe_support_inr'));
                    }

                    $input['web_appointment'] = true;
                    $url = $this->appointmentTransactionRepository->phonePePayment($input);

                    return $this->sendResponse(['url' => $url,'payment_type' => $input['payment_mode']],'PhonePe created successfully');
                }elseif($input['payment_mode'] ==  9) {
                    DB::commit();

                    $input['web_appointment'] = true;
                    return $this->sendResponse(['payStackData' => $input], 'paystack session created successfully.');

                }else{
                    $data = $this->appointmentTransactionRepository->store($appointment);
                }

            }

            if ($input['patient_type'] == 1 && ! empty($input['patient_type'])) {
                $emailExists = User::whereEmail($input['email'])->exists();
                if ($emailExists) {
                    return $this->sendError(__('messages.appointment.old_patient_email_exists'));
                }
                // $appointment = $this->appointmentRepository->createNewAppointment($input);
                $appointmentDepartmentId = $input['department_id'];

                $input['department_id'] = Department::whereName('Patient')->first()->id;
                $input['dob'] = (! empty($input['dob']) || isset($input['dob'])) ? $input['dob'] : null;
                $input['phone'] = (! empty($input['phone']) || isset($input['phone'])) ? $input['phone'] : null;
                $input['password'] = Hash::make($input['password']);
                $userData = Arr::only($input,
                    ['first_name', 'last_name', 'gender', 'password', 'email', 'department_id', 'status']);
                $jsonFields = [];

                foreach ($input as $key => $value) {
                    if (strpos($key, 'field') === 0) {
                        $jsonFields[$key] = $value;
                    }
                }
                $input['custom_field'] = !empty($jsonFields) ? $jsonFields : null;
                $user = User::create($userData);
                if (isset($input['email'])) {
                    $user->sendEmailVerificationNotification();
                }

                $patient = Patient::create(['user_id' => $user->id,'patient_unique_id' => strtoupper(Patient::generateUniquePatientId())]);

                $ownerId = $patient->id;
                $ownerType = Patient::class;
                $user->update(['owner_id' => $ownerId, 'owner_type' => $ownerType]);
                $user->assignRole($input['department_id']);

                if($input['payment_mode'] != 7 && $input['payment_mode'] != 8 && $input['payment_mode'] != 9){
                    $appointment = Appointment::create([
                        'patient_id' => $patient->id,
                        'doctor_id' => $input['doctor_id'],
                        'department_id' => $appointmentDepartmentId,
                        'opd_date' => $input['opd_date'],
                        'problem' => $input['problem'],
                        'custom_field' => $jsonFields,
                        'payment_type' => $input['payment_mode'] ?? 0,
                    ]);
                }
                $appointment['appointment_charge'] = $input['appointment_charge'];

                if($input['payment_mode'] == 3){
                    DB::commit();
                    $result = $this->appointmentTransactionRepository->WebAppointmentstripeSession($appointment);

                    return $this->sendResponse([
                        'appointment_id' => $appointment->id,
                        'payment_type' => $input['payment_mode'],
                        $result
                    ],'Stripe session created successfully');

                } elseif($input['payment_mode'] == 4){
                    DB::commit();
                    return $this->sendResponse([
                        'appointment_id' => $appointment->id,
                        'payment_type' => $input['payment_mode'],
                        'amount' =>  $appointment->appointment_charge,
                    ],'Razorpay session created successfully');
                } elseif($input['payment_mode'] ==  5) {
                    DB::commit();
                    return $this->sendResponse([
                        'appointment_id' => $appointment->id,
                        'payment_type' => $input['payment_mode'],
                        'amount' =>  $appointment->appointment_charge,
                    ],'paypal session created successfully');

                } elseif($input['payment_mode'] ==  8) {
                    DB::commit();

                    if(!in_array(strtoupper(getCurrentCurrency()),getFlutterWaveSupportedCurrencies())){
                        return $this->sendError(__('messages.payment.flutterwave_not_support'));
                    }

                    $this->setFlutterWaveCredential();
                    $input['patient_id'] = $patient->id;
                    $input['web_appointment'] = true;
                    $url = $this->appointmentTransactionRepository->flutterWavePayment($input);

                    return $this->sendResponse(['url' => $url,'payment_type' => $input['payment_mode']],'FlutterWave created successfully');

                } elseif($input['payment_mode'] ==  7) {
                    DB::commit();

                    if (strtoupper(getCurrentCurrency()) != 'INR') {
                        return $this->sendError(__('messages.payment.phonepe_support_inr'));
                    }

                    $input['web_appointment'] = true;
                    $input['patient_id'] = $patient->id;
                    $url = $this->appointmentTransactionRepository->phonePePayment($input);

                    return $this->sendResponse(['url' => $url,'payment_type' => $input['payment_mode']],'PhonePe created successfully');
                }elseif($input['payment_mode'] ==  9) {
                    DB::commit();

                    $input['web_appointment'] = true;
                    $input['patient_id'] = $patient->id;
                    return $this->sendResponse(['payStackData' => $input], 'paystack session created successfully.');

                }else{
                    $data = $this->appointmentTransactionRepository->store($appointment);
                    DB::commit();
                    return $this->sendSuccess(__('messages.web_menu.appointment').' '.__('messages.common.saved_successfully'));
                }

            }

            DB::commit();

            return $this->sendSuccess(__('messages.web_menu.appointment').' '.__('messages.common.saved_successfully'));
        } catch (\Exception $e) {
            DB::rollBack();
            // return $this->sendError(__('messages.appointment.appointment_exists'));
            return $this->sendError($e->getMessage());
        }
    }

    public function getDoctors(Request $request)
    {
        $id = $request->get('id');

        $doctors = $this->appointmentRepository->getDoctors($id);

        return $this->sendResponse($doctors, 'Retrieved successfully');
    }

    public function getDoctorList(Request $request)
    {
        $id = $request->get('id');
        $doctorArr = $this->appointmentRepository->getDoctorList($id);

        return $this->sendResponse($doctorArr, 'Retrieved successfully');
    }

    public function getBookingSlot(Request $request)
    {
        $inputs = $request->all();
        $inputs['editSelectedDate'] = Carbon::parse($inputs['editSelectedDate'])->format('Y-m-d');
        $data = $this->appointmentRepository->getBookingSlot($inputs);

        return $this->sendResponse($data, 'Retrieved successfully');
    }

    public function getPatientDetails($email)
    {
        $patient = Patient::with('patientUser')->get()->where('patientUser.status', '=', 1)->where('patientUser.email', $email)->first();
        $data = null;
        if ($patient != null) {
            $data = [
                $patient->id => $patient->patientUser->full_name,
            ];
        }

        return $this->sendResponse($data, 'User Retrieved Successfully');
    }

    public function getDoctorsCharge(Request $request)
    {
        $doctorCharge = $this->appointmentRepository->getDoctorsAppointmentCharge($request->id);

        return $this->sendResponse($doctorCharge, 'Doctors Change Retrieved successfully');
    }

}
