<?php

namespace App\Queries;

use App\Models\IpdCharge;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class IpdChargesDataTable
 */
class IpdChargesDataTable
{
    public function get(int $ipdPatientDepartmentId): Builder
    {
        return IpdCharge::with(['chargecategory', 'charge'])->where('ipd_patient_department_id',
            $ipdPatientDepartmentId)
            ->select('ipd_charges.*');
    }
}
