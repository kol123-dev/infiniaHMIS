<?php

namespace App\Repositories;

use App\Mail\AppointmentReminderMail;
use App\Models\Appointment;
use App\Models\AppointmentTransaction;
use App\Models\Department;
use App\Models\Doctor;
use App\Models\DoctorDepartment;
use App\Models\Notification;
use App\Models\Patient;
use App\Models\Receptionist;
use App\Models\Schedule;
use App\Models\User;
use Arr;
use Carbon\Carbon;
use DB;
use Exception;
use Hash;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Mail;
use Str;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Throwable;
use Twilio\Exceptions\ConfigurationException;
use Twilio\Rest\Client;

/**
 * Class appointmentRepository
 *
 * @version February 13, 2020, 5:52 am UTC
 */
class AppointmentRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'patient_id',
        'doctor_id',
        'department_id',
        'opd_date',
        'fee',
    ];

    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    public function model()
    {
        return Appointment::class;
    }

    public function getPatients()
    {
        $patients = Patient::with('patientUser')->get()->where('patientUser.status', '=',
            1)->pluck('patientUser.full_name', 'id')->sort();

        return $patients;
    }

    public function getDoctors($id)
    {
        $doctors = Doctor::with('doctorUser')->where('doctor_department_id', '=', $id)
            ->get()->where('doctorUser.status', '=', 1)->pluck('doctorUser.full_name', 'id')->sort();

        return $doctors;
    }

    public function getDoctorDepartments()
    {
        $departments = DoctorDepartment::all()->pluck('title', 'id')->sort();

        return $departments;
    }

    public function getDoctorDepartmentForAPI()
    {
        $departments = DoctorDepartment::select('id', 'title')->orderBy('id', 'desc')->get();

        return $departments;
    }

    public function getDoctorDepartment($id)
    {
        $departments = Doctor::whereId($id)->with('department')->get();

        return $departments;
    }

    public function sendAppointmentReminder()
    {
        $appointments = Appointment::with('patient.patientUser', 'doctor.doctorUser')
            ->whereDate('opd_date', '=', Carbon::tomorrow()->toDateString())
            ->get();

        $sid = config('twilio.sid');
        $token = config('twilio.token');
        $client = new Client($sid, $token);

        foreach ($appointments as $appointment) {
            try {
                $input['email'] = $appointment->patient->patientUser->email;
                $input['patient_name'] = $appointment->patient->patientUser->full_name;
                $input['patient_phone'] = $appointment->patient->patientUser->phone;
                $input['appointment_date'] = $appointment->opd_date;
                $input['problem'] = $appointment->problem;
                $input['doctor_name'] = $appointment->doctor->doctorUser->full_name;

                $smsMessage = 'You have an appointment tomorrow with '.$appointment->doctor->doctorUser->full_name.
                    "\n\n Appointment Time : ".Carbon::parse($appointment->opd_date)->format('jS M, Y g:i A').
                    "\n\n Thanks";

                Mail::to($input['email'])
                    ->send(new AppointmentReminderMail('emails.appointment_reminder',
                        'Doctor Appointments',
                        $input));

                $client->messages->create(
                    $input['patient_phone'],
                    [
                        'from' => config('twilio.from_number'),
                        'body' => $smsMessage,
                    ]
                );
            } catch (Exception $e) {
                throw new UnprocessableEntityHttpException($e->getMessage());
            }
        }
    }

    public function getBookingSlot($inputs)
    {
        $data['bookingSlotArr'] = [];
        $bookingSlots = Appointment::whereDoctorId($inputs['doctor_id'])->whereDate('opd_date',
            $inputs['editSelectedDate'])->get();

        foreach ($bookingSlots as $bookingSlot) {
            $slotTime = Carbon::parse($bookingSlot->opd_date)->toTimeString();
            $onlyTime = \Str::substr($slotTime, 0, 5);
            $data['bookingSlotArr'][] = $onlyTime;
        }
        
        if (isset($inputs['editId'])) {
            $editTime = Appointment::whereId($inputs['editId'])->get();
            $editSlotTime = Carbon::parse($editTime[0]->opd_date)->toTimeString();
            $data['onlyTime'] = \Str::substr($editSlotTime, 0, 5);
        }

        return $data;
    }

    public function getBookingSlotAPI($input)
    {
        $data['bookingSlotArr'] = [];
        $appointmentDate = $input['editSelectedDate'];
        $bookingSlots = Schedule::whereDoctorId($input['doctor_id'])->get();
        foreach ($bookingSlots as $bookingSlot) {
            $available_from = $appointmentDate.' '.$bookingSlot->scheduleDays[0]->available_from;
            $available_to = $appointmentDate.' '.$bookingSlot->scheduleDays[0]->available_to;
            $per_patient_time = explode(':', $bookingSlot->per_patient_time);
            $minutes = ($per_patient_time[0]) * 60 + ($per_patient_time[1]);
            $start_time = $this->appointmentParseIn($available_from);
            $end_time = $this->appointmentParseIn($available_to);
            $data['bookingSlotArr'] = $this->appointmentGetTimeIntervals($start_time, $end_time, $minutes);
        }

        return $data;
    }

    public function appointmentParseIn($time)
    {
        $date_time = new Carbon();

        $date_time->hour(Str::substr($time, 11, 5));
        $date_time->minute(Str::substr($time, 14, 5));

        return \Illuminate\Support\Carbon::parse($date_time)->format('H:i');
    }

    public function appointmentGetTimeIntervals($start_time, $end_time, $per_patient_time)
    {
        $arr = [];
        $start_time = \Illuminate\Support\Carbon::parse($start_time);
        $end_time = \Illuminate\Support\Carbon::parse($end_time);
        while ($start_time < $end_time) {
            $array = $start_time->setMinute($start_time->minute + $per_patient_time);
            $arr[] = \Illuminate\Support\Carbon::parse($array)->format('h:i A');
        }

        return $arr;
    }

    public function getTime($inputs)
    {
        $data['bookingSlotArr'] = [];
        $bookingSlots = Appointment::whereDoctorId($inputs['doctor_id'])->whereDate('opd_date',
            $inputs['opd_date'])->get();
        //        dd(Appointment::whereDoctorId($inputs['doctor_id'])->get());
        foreach ($bookingSlots as $bookingSlot) {
            $slotTime = Carbon::parse($bookingSlot->opd_date)->toTimeString();
            $data['bookingSlotArr'][] = $slotTime;
        }

        return $data;
    }

    public function sendAppointmentEmailBeforeOneHour()
    {
        $startTime = Carbon::now()->addHour()->toDateTimeString();
        $endTime = Carbon::now()->addHours(2)->toDateTimeString();

        $appointments = Appointment::with('patient.patientUser', 'doctor.doctorUser')
            ->where('opd_date', '>=', $startTime)
            ->where('opd_date', '<=', $endTime)
            ->get();

        foreach ($appointments as $appointment) {
            try {
                $input['patient_email'] = $appointment->patient->patientUser->email;
                $input['patient_name'] = $appointment->patient->patientUser->full_name;
                $input['appointment_date'] = $appointment->opd_date;
                $input['problem'] = $appointment->problem;
                $input['doctor_name'] = $appointment->doctor->doctorUser->full_name;
                $input['doctor_email'] = $appointment->doctor->doctorUser->email;

                Mail::to($input['patient_email'])
                    ->send(new AppointmentReminderMail('emails.appointment_reminder_patient',
                        'You have an appointment with Dr.'.$input['doctor_name'],
                        $input));
                Mail::to($input['doctor_email'])
                    ->send(new AppointmentReminderMail('emails.appointment_reminder_doctor',
                        'You have an appointment with '.$input['patient_name'],
                        $input));
            } catch (Exception $e) {
                throw new UnprocessableEntityHttpException($e->getMessage());
            }
        }

        return true;
    }

    public function createNotification($input)
    {
        try {
            $patient = Patient::with('patientUser')->where('id', $input['patient_id'])->first();
            $doctor = Doctor::with('doctorUser')->where('id', $input['doctor_id'])->pluck('user_id', 'id')->first();
            $receptionists = Receptionist::pluck('user_id', 'id')->toArray();
            $userIds = [
                $doctor => Notification::NOTIFICATION_FOR[Notification::DOCTOR],
                $patient->user_id => Notification::NOTIFICATION_FOR[Notification::PATIENT],
            ];
            foreach ($receptionists as $key => $userId) {
                $userIds[$userId] = Notification::NOTIFICATION_FOR[Notification::RECEPTIONIST];
            }

            $adminUser = User::role('Admin')->first();
            $allUsers = $userIds + [$adminUser->id => Notification::NOTIFICATION_FOR[Notification::ADMIN]];
            $users = getAllNotificationUser($allUsers);

            foreach ($users as $key => $notification) {
                if ($notification == Notification::NOTIFICATION_FOR[Notification::PATIENT]) {
                    $title = $patient->patientUser->full_name.' your appointment has been booked.';
                } else {
                    $title = $patient->patientUser->full_name.' appointment has been booked.';
                }
                addNotification([
                    Notification::NOTIFICATION_TYPE['Appointment'],
                    $key,
                    $notification,
                    $title,
                ]);
            }
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function createNewAppointment($input)
    {
        try {
            DB::beginTransaction();

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

            $appointment = Appointment::create([
                'patient_id' => $patient->id,
                'doctor_id' => $input['doctor_id'],
                'department_id' => $appointmentDepartmentId,
                'opd_date' => $input['opd_date'],
                'problem' => $input['problem'],
                'custom_field' => $jsonFields,
                'payment_type' => $input['payment_mode'] ?? 0,
            ]);

            DB::commit();

            return $appointment;
        } catch (Exception $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function getDoctorLists()
    {
        $doctors = Doctor::with('doctorUser')->get()->where('doctorUser.status', '=', 1)->pluck('doctorUser.full_name',
            'id');

        return $doctors;
    }

    public function getDoctorList($id)
    {
        $doctors = Doctor::whereId($id)->with('doctorUser')->get()->where('doctorUser.status', '=',
            1)->pluck('doctorUser.full_name',
                'id');

        return $doctors;
    }

    public function filter($status)
    {
        if ($status == 'all') {
            return Appointment::with('patient', 'doctor', 'department')->orderBy('id', 'desc')->get();
        } elseif ($status == 'pending') {
            return Appointment::where('is_completed', Appointment::STATUS_PENDING)->where('patient_id',
                getLoggedInUser()->owner_id)->with('patient', 'doctor', 'department')->orderBy('id', 'desc')->get();
        } elseif ($status == 'completed') {
            return Appointment::where('is_completed', Appointment::STATUS_COMPLETED)->where('patient_id',
                getLoggedInUser()->owner_id)->with('patient', 'doctor', 'department')->orderBy('id', 'desc')->get();
        } elseif ($status == 'cancelled') {
            return Appointment::where('is_completed', Appointment::STATUS_CANCELLED)->where('patient_id',
                getLoggedInUser()->owner_id)->with('patient', 'doctor', 'department')->orderBy('id', 'desc')->get();
        } elseif ($status == 'past') {
            return Appointment::whereDate('opd_date', '<', Carbon::today())->where('patient_id',
                getLoggedInUser()->owner_id)->with('patient', 'doctor', 'department')->orderBy('id', 'desc')->get();
        } else {
            return false;
        }
    }

    public function getDepartmentDoctorList($id)
    {
        $doctors = Doctor::where('doctor_department_id', $id)->with('doctorUser')->orderBy('id',
            'desc')->get()->where('doctorUser.status', '=',
                1);

        $data = [];
        foreach ($doctors as $doctor) {
            $data[] = $doctor->prepareDoctorData();
        }

        return $data;
    }

    public function getDoctorsAppointmentCharge($id)
    {
        $doctorCharge = Doctor::find($id);

        return $doctorCharge->appointment_charge;
    }


}
