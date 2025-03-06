<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $input = [
            'Admin',
            'Doctor',
            'Patient',
            'Nurse',
            'Receptionist',
            'Pharmacist',
            'Accountant',
            'Case Manager',
            'Lab Technician',
        ];

        foreach ($input as $value) {
            Department::create([
                'name' => $value,
                'is_active' => true,
            ]);
        }
    }
}
