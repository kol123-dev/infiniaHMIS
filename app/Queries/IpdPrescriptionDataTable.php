<?php

namespace App\Queries;

use App\Models\IpdPrescription;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class IpdPrescriptionDataTable
 */
class IpdPrescriptionDataTable
{
    public function get(int $ipdPatientDepartmentId): Builder
    {
        return IpdPrescription::with('patient')->where('ipd_patient_department_id', $ipdPatientDepartmentId)
            ->select('ipd_prescriptions.*');
    }
}
