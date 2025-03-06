<?php

namespace App\Queries;

use App\Models\DoctorDepartment;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class DoctorDepartmentDataTable.
 */
class DoctorDepartmentDataTable
{
    public function get(): Builder
    {
        /** @var DoctorDepartment $query */
        return DoctorDepartment::query();
    }
}
