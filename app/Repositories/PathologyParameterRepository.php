<?php

namespace App\Repositories;

use App\Models\PathologyParameter;
use App\Models\PathologyUnit;

/**
 * Class PathologyCategoryRepository
 *
 * @version April 11, 2020, 5:39 am UTC
 */
class PathologyParameterRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'parameter_name',
        'reference_range',
        'unit_id',
        'description',
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model()
    {
        return PathologyParameter::class;
    }

    public function getPathologyUnitData()
    {
        $data = PathologyUnit::all()->pluck('name','id');

        return $data;
    }
}
