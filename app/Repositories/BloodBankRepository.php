<?php

namespace App\Repositories;

use App\Models\BloodBank;

/**
 * Class BloodBankRepository
 *
 * @version February 17, 2020, 9:23 am UTC
 */
class BloodBankRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'blood_group',
        'remained_bags',
    ];

    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    public function model()
    {
        return BloodBank::class;
    }
}
