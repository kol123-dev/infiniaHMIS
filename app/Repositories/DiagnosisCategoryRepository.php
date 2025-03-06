<?php

namespace App\Repositories;

use App\Models\DiagnosisCategory;

class DiagnosisCategoryRepository extends BaseRepository
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
        return DiagnosisCategory::class;
    }
}
