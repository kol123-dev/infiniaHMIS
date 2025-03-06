<?php

namespace App\Queries;

use App\Models\OpdPrescription;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class IpdPrescriptionDataTable
 */
class OpdPrescriptionDataTable
{
    public function get(int $opdPatientDepartmentId): Builder
    {
        return OpdPrescription::with('patient')->where('opd_patient_department_id', $opdPatientDepartmentId)
            ->select('opd_prescriptions.*');
    }
}
