<?php

namespace App\Repositories;

use App\Models\Account;
use App\Models\Payment;

/**
 * Class PaymentRepository
 *
 * @version February 22, 2020, 7:06 am UTC
 */
class PaymentRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'payment_date',
        'account_id',
        'pay_to',
        'amount',
    ];

    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    public function model()
    {
        return Payment::class;
    }

    public function getAccounts()
    {
        $accounts = Account::where('status', '=', 1)->pluck('name', 'id')->sort();

        return $accounts;
    }
}
