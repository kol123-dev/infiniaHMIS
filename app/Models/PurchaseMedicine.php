<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PurchaseMedicine extends Model
{
    protected $fillable =
        [
            'purchase_no',
            'total',
            'discount',
            'tax',
            'net_amount',
            'payment_type',
            'payment_status',
            'payment_note',
            'note',
            'tenant_id',
        ];

    const PURCHASE_MEDICINE_CASH = 0;

    const PURCHASE_MEDICINE_CHEQUE = 1;

    const PURCHASE_MEDICINE_STRIPE = 5;

    const PURCHASE_MEDICINE_RAZORPAY = 2;

    const PURCHASE_MEDICINE_PAYSTACK = 3;

    const PURCHASE_MEDICINE_PHONEPE = 4;

    const PURCHASE_MEDICINE_FLUTTERWAVE = 6;

    public function purchasedMedcines(): HasMany
    {
        return $this->hasMany(PurchasedMedicine::class, 'purchase_medicines_id');
    }
}
