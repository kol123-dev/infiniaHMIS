<?php

namespace App\Repositories;

use App\Models\Department;

/**
 * Class DepartmentRepository
 *
 * @version February 12, 2020, 5:39 am UTC
 */
class DepartmentRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'name',
        'is_active',
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model()
    {
        return Department::class;
    }
}
