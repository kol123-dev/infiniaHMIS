<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 *  App\Models\OpdPrescription
 *
 * @property int $id
 * @property int $opd_patient_department_id
 * @property string|null $header_note
 * @property string|null $footer_note
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OpdPrescription newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OpdPrescription newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OpdPrescription query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OpdPrescription whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OpdPrescription whereOpdPatientDepartmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OpdPrescription whereHeaderNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OpdPrescription whereFooterNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OpdPrescription whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OpdPrescription whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 * @property-read Collection|OpdPrescriptionItem[] $opdPrescriptionItems
 * @property-read int|null $opd_prescription_items_count
 * @property-read Medicine $medicine
 * @property-read Category $medicineCategory
 */

class OpdPrescription extends Model
{
    public $table = 'opd_prescriptions';

    protected $fillable = [
        'opd_patient_department_id',
        'header_note',
        'footer_note',
    ];

    protected $casts = [
        'id' => 'integer',
        'opd_patient_department_id' => 'integer',
        'header_note' => 'string',
        'footer_note' => 'string',
    ];

    public static $rules = [
        'category_id.*' => 'required',
        'dosage.*' => 'required',
        'day.*' => 'required',
        'dose_interval.*' => 'required',
        'time.*' => 'required',
        'instruction.*' => 'required',
    ];

    public function opdPrescriptionItems(): HasMany
    {
        return $this->hasMany(OpdPrescriptionItem::class, 'opd_prescription_id');
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(OpdPatientDepartment::class, 'opd_patient_department_id');
    }
}
