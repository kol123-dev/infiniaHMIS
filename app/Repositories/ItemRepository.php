<?php

namespace App\Repositories;

use App\Models\Item;
use App\Models\ItemCategory;

/**
 * Class ItemRepository
 *
 * @version August 26, 2020, 10:11 am UTC
 */
class ItemRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'name',
        'item_category_id',
        'unit',
        'description',
        'avaiable_quantity',
    ];

    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    public function model()
    {
        return Item::class;
    }

    public function getItemCategories()
    {
        return ItemCategory::all()->pluck('name', 'id')->sort();
    }
}
