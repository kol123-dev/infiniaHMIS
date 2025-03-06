<?php

namespace App\Repositories;

use App\Models\BedType;

/**
 * Class BedTypeRepository
 *
 * @version February 17, 2020, 8:08 am UTC
 */
class BedTypeRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'title',
        'description',
    ];

    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    public function model()
    {
        return BedType::class;
    }
}
