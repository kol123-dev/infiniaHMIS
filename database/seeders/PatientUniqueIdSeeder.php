<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Patient;

class PatientUniqueIdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $patients = Patient::whereNull('patient_unique_id')->get();

        foreach($patients as $patient){
            $uniqueId = Patient::generateUniquePatientId();
            $patient->update(['patient_unique_id' => strtoupper($uniqueId)]);
        }
    }
}
