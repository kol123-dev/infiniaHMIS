<?php

namespace App\Repositories;

use App\Models\CurrencySetting;

/**
 * Class CurrencySettingRepository
 *
 * @version September 30, 2022, 7:29 pm UTC
 */
class CurrencySettingRepository extends BaseRepository
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
        return CurrencySetting::class;
    }
}
