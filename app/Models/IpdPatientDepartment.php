<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Str;

/**
 * Class IpdPatientDepartment
 *
 * @version September 8, 2020, 6:42 am UTC
 *
 * @property int $patient_id
 * @property string $ipd_number
 * @property string $height
 * @property string $weight
 * @property string $bp
 * @property string $symptoms
 * @property string $notes
 * @property string $admission_date
 * @property int $case_id
 * @property bool $is_old_patient
 * @property int $doctor_id
 * @property int $bed_type_id
 * @property int $bed_id
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\IpdPatientDepartment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\IpdPatientDepartment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\IpdPatientDepartment query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\IpdPatientDepartment whereAdmissionDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\IpdPatientDepartment whereBedId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\IpdPatientDepartment whereBedTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\IpdPatientDepartment whereBp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\IpdPatientDepartment whereCaseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\IpdPatientDepartment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\IpdPatientDepartment whereDoctorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\IpdPatientDepartment whereHeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\IpdPatientDepartment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\IpdPatientDepartment whereIpdNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\IpdPatientDepartment whereIsOldPatient($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\IpdPatientDepartment whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\IpdPatientDepartment wherePatientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\IpdPatientDepartment whereSymptoms($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\IpdPatientDepartment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\IpdPatientDepartment whereWeight($value)
 *
 * @mixin \Eloquent
 *
 * @property-read Bed $bed
 * @property-read BedType|null $bedType
 * @property-read Doctor|null $doctor
 * @property-read Patient $patient
 * @property-read PatientCase|null $patientCase
 * @property-read BedAssign $bedAssign
 * @property int $bill_status
 * @property-read \App\Models\IpdBill|null $bill
 *
 * @method static \Illuminate\Database\Eloquent\Builder|IpdPatientDepartment whereBillStatus($value)
 */
class IpdPatientDepartment extends Model
{
    const STATUS_ARR = [
        '' => 'All',
        0 => 'Active',
        1 => 'Discharged',
    ];

    const FILTER_STATUS_ARR = [
        0 => 'All',
        1 => 'Active',
        2 => 'Discharged',
    ];

    public static $rules = [
        'patient_id' => 'required',
        'admission_date' => 'required',
        'doctor_id' => 'required',
        'bed_type_id' => 'required',
        'bed_id' => 'required',
        'case_id' => 'required',
        'weight' => 'numeric|max:200|nullable',
        'height' => 'numeric|nullable',
        'bp' => 'numeric|max:200|nullable',
    ];

    public $table = 'ipd_patient_departments';

    public $fillable = [
        'patient_id',
        'ipd_number',
        'height',
        'weight',
        'bp',
        'symptoms',
        'notes',
        'admission_date',
        'case_id',
        'is_old_patient',
        'doctor_id',
        'bed_type_id',
        'bed_id',
        'discharge',
        'custom_field',
    ];

    protected $casts = [
        'id' => 'integer',
        'patient_id' => 'integer',
        'ipd_number' => 'string',
        'height' => 'integer',
        'weight' => 'integer',
        'bp' => 'string',
        'symptoms' => 'string',
        'notes' => 'string',
        'case_id' => 'integer',
        'is_old_patient' => 'boolean',
        'doctor_id' => 'integer',
        'bed_type_id' => 'integer',
        'bed_id' => 'integer',
        'discharge' => 'boolean',
        'custom_field' => 'array',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function patientCase(): BelongsTo
    {
        return $this->belongsTo(PatientCase::class, 'case_id');
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }

    public function bedType(): BelongsTo
    {
        return $this->belongsTo(BedType::class, 'bed_type_id');
    }

    public function bed(): BelongsTo
    {
        return $this->belongsTo(Bed::class, 'bed_id');
    }

    public function bedAssign(): BelongsTo
    {
        return $this->belongsTo(BedAssign::class, 'bed_id');
    }

    public function bill()
    {
        return $this->hasOne(IpdBill::class, 'ipd_patient_department_id');
    }

    public static function generateUniqueIpdNumber()
    {
        $ipdNumber = strtoupper(Str::random(8));
        while (true) {
            $isExist = self::whereIpdNumber($ipdNumber)->exists();
            if ($isExist) {
                self::generateUniqueIpdNumber();
            }
            break;
        }

        return $ipdNumber;
    }
}
