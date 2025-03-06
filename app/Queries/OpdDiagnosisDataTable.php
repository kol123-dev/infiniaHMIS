<?php

namespace App\Queries;

use App\Models\OpdDiagnosis;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class OpdDiagnosisDataTable
 */
class OpdDiagnosisDataTable
{
    public function get(int $opdPatientDepartmentId): Builder
    {
        return OpdDiagnosis::whereOpdPatientDepartmentId($opdPatientDepartmentId)->select('opd_diagnoses.*');
    }
}
