<?php

namespace App\Repositories;

use App\Models\Brand;

/**
 * Class BrandRepository
 *
 * @version February 13, 2020, 4:28 am UTC
 */
class BrandRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'name',
        'seller',
        'email',
        'phone',
    ];

    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    public function model()
    {
        return Brand::class;
    }
}
