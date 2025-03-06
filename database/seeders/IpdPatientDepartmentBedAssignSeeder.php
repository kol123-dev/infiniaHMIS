<?php

namespace Database\Seeders;

use App\Models\Bed;
use App\Models\BedAssign;
use App\Models\IpdPatientDepartment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IpdPatientDepartmentBedAssignSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = BedAssign::with('ipdPatient')->where('status', 0)->whereHas('ipdPatient', function ($q) {
            $q->where('discharge', 0);
        })->latest()->update(['status' => 1]);
    }
}
