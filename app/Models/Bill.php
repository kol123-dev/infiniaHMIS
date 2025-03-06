<?php

namespace App\Models;

use App\Repositories\BillRepository;
use \PDF;
use Carbon\Carbon;
use Eloquent as Model;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Storage;
use Str;

/**
 * Class Bill
 *
 * @version February 13, 2020, 9:47 am UTC
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static Builder|Bill newModelQuery()
 * @method static Builder|Bill newQuery()
 * @method static Builder|Bill query()
 * @method static Builder|Bill whereAmount($value)
 * @method static Builder|Bill whereBillDate($value)
 * @method static Builder|Bill whereCreatedAt($value)
 * @method static Builder|Bill whereId($value)
 * @method static Builder|Bill wherePatientId($value)
 * @method static Builder|Bill whereUpdatedAt($value)
 *
 * @mixin Model
 *
 * @property int $patient_id
 * @property \Illuminate\Support\Carbon $bill_date
 * @property float $amount
 * @property-read Collection|BillItems[] $billItems
 * @property-read int|null $bill_items_count
 * @property-read User $patient
 * @property string $patient_admission_id
 *
 * @method static Builder|Bill wherePatientAdmissionId($value)
 *
 * @property string $bill_id
 * @property-read \App\Models\PatientAdmission $patientAdmission
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bill whereBillId($value)
 *
 * @property int $is_default
 *
 * @method static Builder|Bill whereIsDefault($value)
 */
class Bill extends Model
{
    public $table = 'bills';

    public $fillable = [
        'patient_admission_id',
        'patient_id',
        'bill_id',
        'bill_date',
        'amount',
        'currency_symbol',
        'payment_mode',
        'status',
    ];

    protected $casts = [
        'id' => 'integer',
        'patient_admission_id' => 'string',
        'currency_symbol' => 'string',
        'patient_id' => 'integer',
        'bill_id' => 'string',
        'bill_date' => 'datetime',
        'amount' => 'double',
        'payment_mode' => 'string',
        'status' => 'string',
    ];

    public static $rules = [
        'patient_id' => 'required|integer|min:1',
        'bill_date' => 'required|string',
    ];

    const Stripe = 0;
    const Manually = 1;
    const Razorpay = 2;
    const Flutterwave = 8;
    const PhonePe = 5;
    const Paystack = 3;

    const PAYEMENT_TYPE_ARR = [
        self::Stripe => 'Stripe',
        self::Manually => 'Manually',
        self::Razorpay => 'Razorpay',
        self::Flutterwave => 'Flutterwave',
        self::PhonePe => 'PhonePe',
        self::Paystack => 'Paystack',
    ];

    public function billItems(): HasMany
    {
        return $this->hasMany(BillItems::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function setBillDateAttribute($value)
    {
        $this->attributes['bill_date'] = Carbon::parse($value)->format('Y-m-d H:i:s');
    }

    public function patientAdmission(): HasOne
    {
        return $this->hasOne(PatientAdmission::class, 'patient_admission_id', 'patient_admission_id');
    }

    public function manualBillPayment() : HasMany
    {
        return $this->hasMany(ManualBillPayment::class, 'bill_id');
    }

    public static function generateUniqueBillId()
    {
        $billId = mb_strtoupper(Str::random(6));
        while (true) {
            $isExist = self::whereBillId($billId)->exists();
            if ($isExist) {
                self::generateUniqueBillId();
            }
            break;
        }

        return $billId;
    }

    public function prepareBillItems()
    {
        $data = [];
        foreach ($this->billItems as $bill_item) {
            $data[] = [
                'id' => $bill_item->id ?? __('messages.common.n/a'),
                'item_name' => $bill_item->item_name ?? __('messages.common.n/a'),
                'quantity' => $bill_item->qty ?? __('messages.common.n/a'),
                'price' => $bill_item->price ?? __('messages.common.n/a'),
                'total' => $bill_item->amount ?? __('messages.common.n/a'),
            ];
        }

        return $data;
    }

    public function prepareBills()
    {
        return [
            'id' => $this->id ?? __('messages.common.n/a'),
            'bill_id' => $this->bill_id ?? __('messages.common.n/a'),
            'bill_time' => Carbon::parse($this->bill_date)->format('g:i A') ?? __('messages.common.n/a'),
            'bill_date' => Carbon::parse($this->bill_date)->format('jS M, Y') ?? __('messages.common.n/a'),
            'amount' => $this->amount ?? __('messages.common.n/a'),
            'currency' => getCurrencySymbol() ?? __('messages.common.n/a'),
        ];
    }

    public function prepareBillDetails()
    {
        $admissionDate = Carbon::parse($this->patientAdmission->admission_date);
        $dischargeDate = Carbon::parse($this->patientAdmission->discharge_date);

        return [
            'id' => $this->id,
            'bill_id' => $this->bill_id,
            'bill_time' => isset($this->bill_date) ? Carbon::parse($this->bill_date)->format('g:i A') : __('messages.common.n/a'),
            'bill_date' => isset($this->bill_date) ? Carbon::parse($this->bill_date)->format('jS M, Y') : __('messages.common.n/a'),
            'amount' => $this->amount ?? __('messages.common.n/a'),
            'currency' => getCurrencySymbol() ?? __('messages.common.n/a'),
            'patient_admission_id' => $this->patient_admission_id ?? __('messages.common.n/a'),
            'admission_detail' => [
                'phone' => getLoggedInUser()->phone ?? __('messages.common.n/a'),
                'doctor' => $this->patientAdmission->doctor->doctorUser->full_name,
                'admission_date' => isset($this->patientAdmission->admission_date) ? Carbon::parse($this->patientAdmission->admission_date)->format('jS M, Y') : __('messages.common.n/a'),
                'admission_time' => isset($this->patientAdmission->admission_date) ? Carbon::parse($this->patientAdmission->admission_date)->format('g:i A') : __('messages.common.n/a'),
                'discharge_date' => isset($this->patientAdmission->discharge_date) ? Carbon::parse($this->patientAdmission->discharge_date)->format('jS M, Y') : __('messages.common.n/a'),
                'discharge_time' => isset($this->patientAdmission->discharge_date) ? Carbon::parse($this->patientAdmission->discharge_date)->format('g:i A') : __('messages.common.n/a'),
                'created_at' => $this->patientAdmission->created_at ? $this->patientAdmission->created_at->diffForHumans() : __('messages.common.n/a'),
            ],
            'insurance_detail' => [
                'package_name' => $this->patientAdmission->package->name ?? __('messages.common.n/a'),
                'insurance_name' => $this->patientAdmission->insurance->name ?? __('messages.common.n/a'),
                'total_days' => $admissionDate->diffInDays($dischargeDate) + 1,
                'policy_no' => $this->patientAdmission->insurance->policy_no ?? __('messages.common.n/a'),
            ],
            'item_details' => $this->prepareBillItems(),
            'bill_download' => $this->convertToPdf($this->id),
        ];
    }

    public function convertToPdf($id)
    {
        $bill = Bill::with('billItems')->find($id);
        $data = App()->make(billRepository::class)->getSyncListForCreate($id);
        $data['bill'] = $bill;
        if (Storage::exists('bills/Bill-'.$bill->bill_id.'.pdf')) {
            Storage::delete('bills/Bill-'.$bill->bill_id.'.pdf');
        }
        $pdf = PDF::loadView('bills.bill_pdf', $data);
        Storage::disk(config('app.media_disc'))->put('bills/Bill-'.$bill->bill_id.'.pdf', $pdf->output());
        $url = Storage::url('bills/Bill-'.$bill->bill_id.'.pdf');

        return $url ?? __('messages.common.n/a');
    }
}
