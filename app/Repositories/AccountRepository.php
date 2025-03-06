<?php

namespace App\Repositories;

use App\Models\Account;

/**
 * Class AccountRepository
 *
 * @version February 21, 2020, 9:54 am UTC
 */
class AccountRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'name',
        'type',
        'description',
        'status',
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model()
    {
        return Account::class;
    }
}
