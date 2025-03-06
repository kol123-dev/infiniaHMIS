<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
/**
 * App\Models\AppointmentTransactions
 *
 * @property int $id
 * @property string $transaction_id
 * @property int $payment_type
 * @property int $amount
 * @property int $appointment_id
 *
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Appointment $appointment
 *
 * @method static Builder|BedType newModelQuery()
 * @method static Builder|User newQuery()
 * @method static Builder|User query()
 * @method static Builder|User whereAddressId($value)
 * @method static Builder|User whereCreatedAt($value)
 * @method static Builder|User wheretrsanctionId($value)
 * @method static Builder|User whereAppointmentId($value)
 * @method static Builder|User whereAmount($value)
 * @method static Builder|User wherePaymentType($value)
 */
class AppointmentTransaction extends Model
{
    use HasFactory;
    protected $table = 'appointment_transactions';

    public $fillable = ['transaction_id', 'payment_type', 'amount', 'appointment_id'];

    protected $casts = [
        'id' => 'integer',
        'transaction_id' => 'string',
        'payment_type' => 'integer',
        'amount' => 'integer',
        'appointment_id' => 'integer',
    ];

    const PAYMENT_MODES = [
        1 => 'Cash',
        2 => 'Cheque',
        3 => 'Stripe',
        4 => 'Razorpay',
        5 => 'PayPal',
        6 => 'Other',
    ];

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class, 'appointment_id');
    }
}
