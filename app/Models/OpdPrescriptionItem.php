<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class OpdPrescriptionItem
 *
 * @property int $id
 * @property int $opd_prescription_id
 * @property int $category_id
 * @property int $medicine_id
 * @property string $dosage
 * @property int $dose_interval
 * @property string|null $day
 * @property string|null $time
 * @property string $instruction
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OpdPrescriptionItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OpdPrescriptionItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OpdPrescriptionItem query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OpdPrescriptionItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OpdPrescriptionItem whereOpdPrescriptionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OpdPrescriptionItem whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OpdPrescriptionItem whereMedicineId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OpdPrescriptionItem whereDosage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OpdPrescriptionItem whereInstruction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OpdPrescriptionItem whereDoseInterval($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OpdPrescriptionItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OpdPrescriptionItem whereUpdatedAt($value)
 *
 *  @mixin \Eloquent
 * @property-read Category $medicineCategory
 * @property-read Medicine $medicine
 *
 */
class OpdPrescriptionItem extends Model
{

    public $table = 'opd_prescription_items';

    public $fillable = [
        'opd_prescription_id',
        'category_id',
        'medicine_id',
        'dosage',
        'dose_interval',
        'day',
        'time',
        'instruction',
    ];

    protected $casts = [
        'id' => 'integer',
        'opd_prescription_id' => 'integer',
        'category_id' => 'integer',
        'medicine_id' => 'integer',
        'dosage' => 'string',
        'dose_interval' => 'string',
        'day' => 'string',
        'time' => 'string',
        'instruction' => 'string',
    ];

    public static $rules = [
        'category_id' => 'required',
    ];

    public function medicineCategory(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function medicine(): BelongsTo
    {
        return $this->belongsTo(Medicine::class, 'medicine_id');
    }

}
