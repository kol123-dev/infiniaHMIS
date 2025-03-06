<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ManualBillPayment extends Model
{
    use HasFactory;

    public $table = 'bill_transactions';

    public $fillable = [
        'transaction_id',
        'payment_type',
        'amount',
        'bill_id',
        'status',
        'is_manual_payment',
    ];

    protected $casts = [
        'transaction_id' => 'string',
        'payment_type' => 'string',
        'status' => 'string',
        'bill_id' => 'string',
        'is_manual_payment' => 'string',
        'amount' => 'double',
    ];

    const Approved = 1;
    const Rejected = 2;

    const STATUS_ARR = [
        self::Approved => 'Approved',
        self::Rejected => 'Rejected',
    ];

    public function bill() : BelongsTo
    {
        return $this->belongsTo(Bill::class,'bill_id', 'id');
    }
}
