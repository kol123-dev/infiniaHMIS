<?php

namespace App\Repositories;

use App\Models\Appointment;
use App\Models\User;
use Auth;

/**
 * Class AppointmentCalendarRepository
 *
 * @version March 4, 2020, 5:22 am UTC
 */
class AppointmentCalendarRepository
{
    public function model()
    {
        return Appointment::class;
    }

    public function getAppointments()
    {
        $user = Auth::user();

        $appointments = Appointment::with('patient.patientUser', 'doctor.doctorUser');

        if ($user->hasRole('Doctor')) {
            $appointments->where('doctor_id', $user->owner_id);
        }

        if ($user->hasRole(['Patient'])) {
            $appointments->where('patient_id', $user->owner_id);
        }

        $appointments = $appointments->get()->toArray();
        $result = [];
        
        foreach ($appointments as $appointment) {
            $data['id'] = $appointment['id'];
            $data['title'] = $appointment['patient']['patient_user']['full_name'];
            $data['start'] = $appointment['opd_date'];
            $result[] = $data;
        }

        return array_values($result);
    }
}
