<?php

namespace App\Models;

use Carbon\Carbon;
use Eloquent as Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class BirthReport
 *
 * @version February 18, 2020, 9:47 am UTC
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
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BirthReport newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BirthReport newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BirthReport query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BirthReport whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BirthReport whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BirthReport whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BirthReport whereDoctorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BirthReport whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BirthReport wherePatientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BirthReport whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 *
 * @property string $case_id
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BirthReport whereCaseId($value)
 *
 * @property int $is_default
 * @property-read \App\Models\PatientCase $caseFromBirthReport
 *
 * @method static \Illuminate\Database\Eloquent\Builder|BirthReport whereIsDefault($value)
 */
class BirthReport extends Model
{
    public $table = 'birth_reports';

    public $fillable = [
        'patient_id',
        'case_id',
        'doctor_id',
        'date',
        'description',
    ];

    protected $casts = [
        'id' => 'integer',
        'case_id' => 'string',
        'patient_id' => 'integer',
        'date' => 'datetime',
        'description' => 'string',
        'doctor_id' => 'integer',
    ];

    public static $rules = [
        'case_id' => 'required|unique:birth_reports,case_id',
        'doctor_id' => 'required',
        'date' => 'required',
        'description' => 'nullable|string',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }

    public function caseFromBirthReport(): BelongsTo
    {
        return $this->belongsTo(PatientCase::class, 'case_id', 'case_id');
    }

    public function prepareBirthReport()
    {
        return [
            'id' => $this->id,
            'patient_name' => $this->patient->patientUser->full_name ?? __('messages.common.n/a'),
            'patient_image' => $this->patient->patientUser->api_image_url ?? __('messages.common.n/a'),
            'case_id' => $this->case_id ?? __('messages.common.n/a'),
            'date' => isset($this->date) ? Carbon::parse($this->date)->format('d F y') : __('messages.common.n/a'),
            'time' => isset($this->date) ? Carbon::parse($this->date)->format('h:i A') : __('messages.common.n/a'),
        ];
    }
}
