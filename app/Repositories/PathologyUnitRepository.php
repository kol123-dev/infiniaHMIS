<?php

namespace App\Repositories;

use App\Models\PathologyUnit;

/**
 * Class PathologyCategoryRepository
 *
 * @version April 11, 2020, 5:39 am UTC
 */
class PathologyUnitRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'name',
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model()
    {
        return PathologyUnit::class;
    }
}
