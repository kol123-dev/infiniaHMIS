<?php

namespace App\Repositories;

use App\Models\ChargeCategory;

/**
 * Class ChargeCategoryRepository
 *
 * @version April 11, 2020, 5:26 am UTC
 */
class ChargeCategoryRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'name',
        'description',
    ];

    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    public function model()
    {
        return ChargeCategory::class;
    }
}
