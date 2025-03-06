<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\AppointmentTransaction;
use Illuminate\Database\Seeder;

class ChangeDefaultPayment extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $appointments = Appointment::where('payment_type', 4)->get();

        foreach ($appointments as $appointment) {

            $exists = AppointmentTransaction::where('appointment_id', $appointment->id)->exists();
            if (!$exists) {
                $appointment->update(['payment_type' => 1]);
            }
        }
    }
}
