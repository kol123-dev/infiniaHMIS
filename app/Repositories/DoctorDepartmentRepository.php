<?php

namespace App\Repositories;

use App\Models\DoctorDepartment;

/**
 * Class DoctorDepartmentRepository
 *
 * @version February 21, 2020, 5:23 am UTC
 */
class DoctorDepartmentRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'title',
        'description',
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model()
    {
        return DoctorDepartment::class;
    }
}
