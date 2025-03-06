<?php

namespace App\Repositories;

use App\Models\OperationCategory;

class OperationCategoryRepository extends BaseRepository
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
        return OperationCategory::class;
    }
}
