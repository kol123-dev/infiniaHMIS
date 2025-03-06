<?php

namespace App\Repositories;

use App\Models\Charge;

/**
 * Class ChargeRepository
 *
 * @version April 11, 2020, 9:09 am UTC
 */
class ChargeRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'standard_charge',
        'code',
    ];

    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    public function model()
    {
        return Charge::class;
    }
}
