<?php

namespace App\Repositories;

use App\Models\PathologyCategory;
/**
 * Class PathologyCategoryRepository
 *
 * @version April 11, 2020, 5:39 am UTC
 */
class PathologyCategoryRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'name',
    ];

    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    public function model()
    {
        return PathologyCategory::class;
    }
}
