<?php

namespace Database\Seeders;

use App\Models\Bed;
use App\Models\BedAssign;
use App\Models\IpdBill;
use App\Models\IpdPatientDepartment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IpdPatientDepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datas = IpdPatientDepartment::with('bill')->get();

        if($datas){
            foreach ($datas as $data) {
                if ($data->bill) {
                    $ipdId = $data->bill->ipd_patient_department_id;
                    IpdPatientDepartment::where('id', $ipdId)->update(['discharge' => 1]);

                    $patientIds = IpdPatientDepartment::where('discharge', 1)->pluck('patient_id');
                    BedAssign::whereIn('patient_id', $patientIds)->update(['status' => 0]);

                    $bedIds = BedAssign::pluck('bed_id');
                    Bed::whereIn('id', $bedIds)->update(['is_available' => 1]);
                }
            }
        }

    }
}
