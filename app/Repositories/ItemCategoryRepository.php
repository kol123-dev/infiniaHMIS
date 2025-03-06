<?php

namespace App\Repositories;

use App\Models\ItemCategory;

/**
 * Class ItemCategoryRepository
 *
 * @version August 26, 2020, 8:12 am UTC
 */
class ItemCategoryRepository extends BaseRepository
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
        return ItemCategory::class;
    }
}
