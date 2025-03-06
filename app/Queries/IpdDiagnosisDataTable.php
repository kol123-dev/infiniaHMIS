<?php

namespace App\Queries;

use App\Models\IpdDiagnosis;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class IpdDiagnosisDataTable
 */
class IpdDiagnosisDataTable
{
    public function get(int $ipdPatientDepartmentId): Builder
    {
        return IpdDiagnosis::whereIpdPatientDepartmentId($ipdPatientDepartmentId)->select('ipd_diagnoses.*');
    }
}
