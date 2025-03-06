<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\BloodIssue
 *
 * @property int $id
 * @property string $issue_date
 * @property int $doctor_id
 * @property int $donor_id
 * @property int $patient_id
 * @property float|null $amount
 * @property string|null $remarks
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read BloodDonor $bloodDonor
 * @property-read Doctor $doctor
 * @property-read Patient $patient
 *
 * @method static \Illuminate\Database\Eloquent\Builder|BloodIssue newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BloodIssue newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BloodIssue query()
 * @method static \Illuminate\Database\Eloquent\Builder|BloodIssue whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BloodIssue whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BloodIssue whereDoctorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BloodIssue whereDonorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BloodIssue whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BloodIssue whereIssueDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BloodIssue wherePatientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BloodIssue whereRemarks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BloodIssue whereUpdatedAt($value)
 *
 * @mixin Eloquent
 *
 * @property-read \App\Models\BloodDonor $blooddonor
 */
class BloodIssue extends Model
{
    public $table = 'blood_issues';

    public $fillable = [
        'issue_date',
        'doctor_id',
        'patient_id',
        'donor_id',
        'amount',
        'remarks',
        'currency_symbol',
    ];

    public static $rules = [
        'issue_date' => 'required',
        'patient_id' => 'required',
        'doctor_id' => 'required',
        'donor_id' => 'required',
        'remarks' => 'string|nullable',
    ];

    protected $casts = [
        'issue_date' => 'datetime',
        'patient_id' => 'integer',
        'doctor_id' => 'integer',
        'donor_id' => 'integer',
        'amount' => 'double',
        'remarks' => 'string',
        'currency_symbol' => 'string',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }

    public function blooddonor(): BelongsTo
    {
        return $this->belongsTo(BloodDonor::class, 'donor_id');
    }
}
