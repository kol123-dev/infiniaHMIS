<?php

namespace App\Repositories;

use App\Models\Category;

/**
 * Class CategoryRepository
 *
 * @version February 6, 2020, 3:16 am UTC
 */
class CategoryRepository extends BaseRepository
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
        return Category::class;
    }
}
