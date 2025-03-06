<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class PathologyTest
 *
 * @version April 14, 2020, 9:33 am UTC
 *
 * @property string test_name
 * @property string short_name
 * @property string test_type
 * @property int category_id
 * @property int unit
 * @property string subcategory
 * @property string method
 * @property int report_days
 * @property int charge_category_id
 * @property float standard_charge
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \App\Models\ChargeCategory $chargecategory
 * @property-read \App\Models\RadiologyCategory $radiologycategory
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PathologyTest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PathologyTest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PathologyTest query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PathologyTest whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PathologyTest whereChargeCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PathologyTest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PathologyTest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PathologyTest whereMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PathologyTest whereReportDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PathologyTest whereShortName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PathologyTest whereStandardCharge($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PathologyTest whereSubcategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PathologyTest whereTestName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PathologyTest whereTestType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PathologyTest whereUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PathologyTest whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 *
 * // * @property string $test_name
 * // * @property string $short_name
 * // * @property string $test_type
 * // * @property int $category_id
 * // * @property int|null $unit
 * // * @property string|null $subcategory
 * // * @property string|null $method
 * // * @property int|null $report_days
 * // * @property int $charge_category_id
 * // * @property float $standard_charge
 * @property-read \App\Models\PathologyCategory $pathologycategory
 */
class PathologyTest extends Model
{
    public $table = 'pathology_tests';

    public $fillable = [
        'test_name',
        'short_name',
        'test_type',
        'category_id',
        'unit',
        'subcategory',
        'method',
        'report_days',
        'charge_category_id',
        'standard_charge',
        'patient_id',
    ];

    protected $casts = [
        'id' => 'integer',
        'test_name' => 'string',
        'short_name' => 'string',
        'test_type' => 'string',
        'category_id' => 'integer',
        'unit' => 'integer',
        'subcategory' => 'string',
        'method' => 'string',
        'report_days' => 'integer',
        'charge_category_id' => 'integer',
        'standard_charge' => 'double',
        'patient_id' => 'integer',
    ];

    public static $rules = [
        'test_name' => 'required|unique:pathology_tests,test_name',
        'short_name' => 'required',
        'test_type' => 'required',
        'category_id' => 'required',
        'charge_category_id' => 'required',
        'standard_charge' => 'required',
        'patient_id' => 'required'
    ];

    public function pathologycategory(): BelongsTo
    {
        return $this->belongsTo(PathologyCategory::class, 'category_id');
    }

    public function chargecategory(): BelongsTo
    {
        return $this->belongsTo(ChargeCategory::class, 'charge_category_id');
    }
    public function parameterItems(): HasMany
    {
        return $this->hasMany(PathologyParameterItem::class, 'pathology_id');
    }
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }
}
