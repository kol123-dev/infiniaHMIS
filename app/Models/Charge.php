<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Support\Carbon;

/**
 * Class Charge
 *
 * @version April 11, 2020, 9:09 am UTC
 *
 * @property int $id
 * @property int $charge_type
 * @property int $charge_category_id
 * @property string $code
 * @property float $standard_charge
 * @property string|null $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read ChargeCategory $chargeCategory
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Charge newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Charge newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Charge query()
 * @method static \Illuminate\Database\Eloquent\Builder|Charge whereChargeCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Charge whereChargeType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Charge whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Charge whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Charge whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Charge whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Charge whereStandardCharge($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Charge whereUpdatedAt($value)
 *
 * @mixin Model
 */
class Charge extends Model
{
    public $table = 'charges';

    public $fillable = [
        'charge_type',
        'charge_category_id',
        'code',
        'standard_charge',
        'description',
        'currency_symbol',
    ];

    protected $casts = [
        'id' => 'integer',
        'charge_type' => 'integer',
        'charge_category_id' => 'integer',
        'code' => 'string',
        'standard_charge' => 'double',
        'description' => 'string',
        'currency_symbol' => 'string',
    ];

    public static $rules = [
        'charge_type' => 'required',
        'charge_category_id' => 'required',
        'code' => 'required|unique:charges,code',
        'standard_charge' => 'required',
    ];

    public function chargeCategory()
    {
        return $this->belongsTo(ChargeCategory::class, 'charge_category_id');
    }
}
