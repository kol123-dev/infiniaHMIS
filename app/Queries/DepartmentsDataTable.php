<?php

namespace App\Queries;

use App\Models\Department;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class DepartmentsDataTable
 */
class DepartmentsDataTable
{
    public function get(array $input = []): \Illuminate\Database\Query\Builder
    {
        $query = Department::select('departments.*');

        $query->when(isset($input['is_active']) && $input['is_active'] != Department::ACTIVE_ALL,
            function (Builder $q) use ($input) {
                $q->where('is_active', $input['is_active']);
            });

        return $query;
    }
}
