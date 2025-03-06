<?php

namespace App\Repositories;

use App\Models\RadiologyCategory;

/**
 * Class RadiologyCategoryRepository
 *
 * @version April 11, 2020, 7:08 am UTC
 */
class RadiologyCategoryRepository extends BaseRepository
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
        return RadiologyCategory::class;
    }
}
