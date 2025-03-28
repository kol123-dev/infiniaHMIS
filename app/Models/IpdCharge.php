<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * Class IpdCharge
 *
 * @version September 9, 2020, 1:55 pm UTC
 *
 * @property int $ipd_patient_department_id
 * @property string $date
 * @property int $charge_type_id
 * @property int $charge_category_id
 * @property int $charge_id
 * @property int $standard_charge
 * @property int $applied_charge
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Charge $charge
 * @property-read ChargeCategory $chargeCategory
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\IpdCharge newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\IpdCharge newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\IpdCharge query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\IpdCharge whereAppliedCharge($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\IpdCharge whereChargeCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\IpdCharge whereChargeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\IpdCharge whereChargeTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\IpdCharge whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\IpdCharge whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\IpdCharge whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\IpdCharge whereIpdPatientDepartmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\IpdCharge whereStandardCharge($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\IpdCharge whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 *
 * @property-read \App\Models\ChargeCategory $chargecategory
 * @property-read mixed $charge_type
 */
class IpdCharge extends Model
{
    public $table = 'ipd_charges';

    const  CHARGE_TYPES = [
        1 => 'Investigations',
        2 => 'Operation Theatre',
        3 => 'Others',
        4 => 'Procedures',
        5 => 'Supplier',
    ];

    public $fillable = [
        'ipd_patient_department_id',
        'date',
        'charge_type_id',
        'charge_category_id',
        'charge_id',
        'standard_charge',
        'applied_charge',
        'currency_symbol',
    ];

    protected $casts = [
        'id' => 'integer',
        'ipd_patient_department_id' => 'integer',
        'date' => 'date',
        'charge_type_id' => 'integer',
        'charge_category_id' => 'integer',
        'charge_id' => 'integer',
        'standard_charge' => 'double',
        'applied_charge' => 'double',
        'currency_symbol' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'date' => 'required',
        'charge_type_id' => 'required',
        'charge_category_id' => 'required',
        'charge_id' => 'required',
        'applied_charge' => 'required',
    ];

    protected $appends = ['charge_type'];

    public function chargecategory(): BelongsTo
    {
        return $this->belongsTo(ChargeCategory::class, 'charge_category_id');
    }

    public function charge(): BelongsTo
    {
        return $this->belongsTo(Charge::class, 'charge_id');
    }

    public function getChargeTypeAttribute()
    {
        return self::CHARGE_TYPES[$this->charge_type_id];
    }
}
