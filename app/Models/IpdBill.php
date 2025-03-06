<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\IpdBill
 *
 * @property int $id
 * @property int $ipd_patient_department_id
 * @property float $total_charges
 * @property float $total_payments
 * @property float $gross_total
 * @property int $discount_in_percentage
 * @property int $tax_in_percentage
 * @property float $other_charges
 * @property float $net_payable_amount
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\IpdPatientDepartment $ipdPatient
 *
 * @method static \Illuminate\Database\Eloquent\Builder|IpdBill newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|IpdBill newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|IpdBill query()
 * @method static \Illuminate\Database\Eloquent\Builder|IpdBill whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IpdBill whereDiscountInPercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IpdBill whereGrossTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IpdBill whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IpdBill whereIpdPatientDepartmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IpdBill whereNetPayableAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IpdBill whereOtherCharges($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IpdBill whereTaxInPercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IpdBill whereTotalCharges($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IpdBill whereTotalPayments($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IpdBill whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class IpdBill extends Model
{
    public $table = 'ipd_bills';

    public $fillable = [
        'ipd_patient_department_id',
        'total_charges',
        'total_payments',
        'gross_total',
        'discount_in_percentage',
        'tax_in_percentage',
        'other_charges',
        'net_payable_amount',
    ];

    protected $casts = [
        'id' => 'integer',
        'ipd_patient_department_id' => 'integer',
        'total_charges' => 'double',
        'total_payments' => 'double',
        'gross_total' => 'double',
        'discount_in_percentage' => 'integer',
        'tax_in_percentage' => 'integer',
        'other_charges' => 'double',
        'net_payable_amount' => 'double',
    ];

    public static $rules = [
        'ipd_patient_department_id' => 'required',
        'total_payments' => 'required',
        'gross_total' => 'required',
        'discount_in_percentage' => 'required',
        'tax_in_percentage' => 'required',
        'other_charges' => 'required',
        'net_payable_amount' => 'required',
    ];

    public function ipdPatient(): hasOne
    {
        return $this->belongsTo(IpdPatientDepartment::class, 'ipd_patient_department_id');
    }
}
