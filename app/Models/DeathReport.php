<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class DeathReport
 *
 * @version February 18, 2020, 11:10 am UTC
 *
 * @property int $id
 * @property int $patient_id
 * @property int $doctor_id
 * @property string $date
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Doctor $doctor
 * @property-read \App\Models\User $patient
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeathReport newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeathReport newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeathReport query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeathReport whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeathReport whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeathReport whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeathReport whereDoctorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeathReport whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeathReport wherePatientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeathReport whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 *
 * @property string $case_id
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeathReport whereCaseId($value)
 *
 * @property int $is_default
 * @property-read \App\Models\PatientCase $caseFromDeathReport
 *
 * @method static \Illuminate\Database\Eloquent\Builder|DeathReport whereIsDefault($value)
 */
class DeathReport extends Model
{
    public $table = 'death_reports';

    public $fillable = [
        'patient_id',
        'case_id',
        'doctor_id',
        'date',
        'description',
    ];

    protected $casts = [
        'id' => 'integer',
        'patient_id' => 'integer',
        'case_id' => 'string',
        'doctor_id' => 'integer',
    ];

    public static $rules = [
        'case_id' => 'required|unique:death_reports,case_id',
        'doctor_id' => 'required',
        'date' => 'required',
        'description' => 'nullable|string',
    ];

    public function prepareData()
    {
        return [
            'id' => $this->id,
            'patient_name' => $this->patient->patientUser->full_name,
            'patient_image' => $this->patient->patientUser->getApiImageUrlAttribute(),
            'case_id' => $this->caseFromDeathReport->case_id,
            'date' => isset($this->date) ? \Carbon\Carbon::parse($this->date)->translatedFormat('jS M, Y') : __('messages.common.n/a'),
            'time' => isset($this->date) ? \Carbon\Carbon::parse($this->date)->isoFormat('LT') : __('messages.common.n/a'),
        ];
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }

    public function caseFromDeathReport(): BelongsTo
    {
        return $this->belongsTo(PatientCase::class, 'case_id', 'case_id');
    }
}
