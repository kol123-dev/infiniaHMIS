<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Class InsuranceDisease
 *
 * @property int $id
 * @property int $insurance_id
 * @property string $disease_name
 * @property float $disease_charge
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static Builder|InsuranceDisease newModelQuery()
 * @method static Builder|InsuranceDisease newQuery()
 * @method static Builder|InsuranceDisease query()
 * @method static Builder|InsuranceDisease whereCreatedAt($value)
 * @method static Builder|InsuranceDisease whereDiseaseCharge($value)
 * @method static Builder|InsuranceDisease whereDiseaseName($value)
 * @method static Builder|InsuranceDisease whereId($value)
 * @method static Builder|InsuranceDisease whereInsuranceId($value)
 * @method static Builder|InsuranceDisease whereUpdatedAt($value)
 *
 * @mixin Eloquent
 */
class InsuranceDisease extends Model
{
    public static $rules = [
        'insurance_id' => 'required',
        'disease_name' => 'required',
        'disease_charge' => 'required',
    ];

    public $table = 'insurance_diseases';

    public $fillable = [
        'insurance_id',
        'disease_name',
        'disease_charge',
    ];

    protected $casts = [
        'id' => 'integer',
        'disease_name' => 'string',
        'disease_charge' => 'float',
    ];
}
