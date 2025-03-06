<?php

namespace App\Queries;

use App\Models\IpdConsultantRegister;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class IpdConsultantRegisterDataTable
 */
class IpdConsultantRegisterDataTable
{
    public function get(int $ipdPatientDepartmentId): Builder
    {
        return IpdConsultantRegister::whereHas('doctor.user')->with('doctor.user')->where('ipd_patient_department_id',
            $ipdPatientDepartmentId)
            ->select('ipd_consultant_registers.*');
    }
}
