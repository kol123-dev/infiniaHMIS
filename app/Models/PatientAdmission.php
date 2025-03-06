<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Str;

/**
 * Class PatientAdmission
 *
 * @mixin Model
 *
 * @property int $id
 * @property int $patient_id
 * @property int $doctor_id
 * @property string $admission_date
 * @property string $discharge_date
 * @property int|null $package_id
 * @property string|null $insurance_id
 * @property string|null $policy_no
 * @property string|null $agent_name
 * @property string|null $guardian_name
 * @property string|null $guardian_relation
 * @property string|null $guardian_contact
 * @property string|null $guardian_address
 * @property int|null $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Doctor $doctor
 * @property-read Package|null $package
 * @property-read Patient $patient
 *
 * @method static Builder|PatientAdmission newModelQuery()
 * @method static Builder|PatientAdmission newQuery()
 * @method static Builder|PatientAdmission query()
 * @method static Builder|PatientAdmission whereAdmissionDate($value)
 * @method static Builder|PatientAdmission whereAgentName($value)
 * @method static Builder|PatientAdmission whereCreatedAt($value)
 * @method static Builder|PatientAdmission whereDischargeDate($value)
 * @method static Builder|PatientAdmission whereDoctorId($value)
 * @method static Builder|PatientAdmission whereGuardianAddress($value)
 * @method static Builder|PatientAdmission whereGuardianContact($value)
 * @method static Builder|PatientAdmission whereGuardianName($value)
 * @method static Builder|PatientAdmission whereGuardianRelation($value)
 * @method static Builder|PatientAdmission whereId($value)
 * @method static Builder|PatientAdmission whereInsuranceId($value)
 * @method static Builder|PatientAdmission wherePackageId($value)
 * @method static Builder|PatientAdmission wherePatientId($value)
 * @method static Builder|PatientAdmission wherePolicyNo($value)
 * @method static Builder|PatientAdmission whereStatus($value)
 * @method static Builder|PatientAdmission whereUpdatedAt($value)
 *
 * @property string $patient_admission_id
 * @property-read Insurance|null $insurance
 *
 * @method static Builder|PatientAdmission wherePatientAdmissionId($value)
 *
 * @property int|null $bed_id
 * @property-read Bed|null $bed
 *
 * @method static Builder|PatientAdmission whereBedId($value)
 *
 * @property int $is_default
 *
 * @method static Builder|PatientAdmission whereIsDefault($value)
 */
class PatientAdmission extends Model
{
    public $table = 'patient_admissions';

    const STATUS_ALL = 2;

    const ACTIVE = 1;

    const INACTIVE = 0;

    const STATUS_ARR = [
        self::STATUS_ALL => 'All',
        self::ACTIVE => 'Active',
        self::INACTIVE => 'Deactive',
    ];

    const FILTER_STATUS_ARR = [
        0 => 'All',
        1 => 'Active',
        2 => 'Deactive',
    ];

    public $fillable = [
        'patient_admission_id',
        'patient_id',
        'doctor_id',
        'admission_date',
        'discharge_date',
        'package_id',
        'insurance_id',
        'bed_id',
        'policy_no',
        'agent_name',
        'guardian_name',
        'guardian_relation',
        'guardian_contact',
        'guardian_address',
        'status',
    ];

    protected $casts = [
        'id' => 'integer',
        'patient_admission_id' => 'string',
        'patient_id' => 'integer',
        'doctor_id' => 'integer',
        'package_id' => 'integer',
        'insurance_id' => 'string',
        'policy_no' => 'string',
        'agent_name' => 'string',
        'guardian_name' => 'string',
        'guardian_relation' => 'string',
        'guardian_contact' => 'string',
        'guardian_address' => 'string',
        'status' => 'integer',
        'admission_date' => 'datetime',
        'discharge_date' => 'datetime',
        'bed_id' => 'integer',
    ];

    public static $rules = [
        'patient_id' => 'required',
        'doctor_id' => 'required',
        'admission_date' => 'required',
        'policy_no' => 'string|nullable',
    ];

    public function prepareBedData($beds)
    {
        return [
            'id' => $beds->id,
            'name' => $this->patient->patientUser->full_name,
            'status' => (bool) $beds->is_available,
        ];
    }

    public function prepareBedStatusData()
    {
        return [
            'bed_title' => $this->bed->bedType->title,
            'bed' => $this->prepareBedData($this->bed),
        ];
    }

    public function prepareDataForDetail()
    {
        return [
            'id' => $this->id,
            'admission_id' => $this->patient_admission_id,
            'patient' => $this->patient->patientUser->full_name,
            'admission_date' => isset($this->admission_date) ? date(
                'jS M,Y g:i A',
                strtotime($this->admission_date)
            ) : __('messages.common.n/a'),
            'discharge_date' => isset($this->discharge_date) ? date(
                'jS M,Y g:i A',
                strtotime($this->discharge_date)
            ) : __('messages.common.n/a'),
            'bed' => isset($this->bed) ? $this->bed->name : __('messages.common.n/a'),
            'guardian_name' => $this->guardian_name,
            'guardian_relation' => $this->guardian_relation,
            'guardian_contact' => $this->guardian_contact,
            'guardian_address' => $this->guardian_address,
            'created_on' => $this->created_at->diffForHumans(),
            'package_name' => isset($this->package) ? $this->package->name : __('messages.common.n/a'),
            'insurance_name' => isset($this->insurance) ? $this->insurance->name : __('messages.common.n/a'),
            'agent_name' => $this->agent_name,
            'policy_number' => $this->policy_no,
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

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class, 'package_id');
    }

    public function insurance(): BelongsTo
    {
        return $this->belongsTo(Insurance::class, 'insurance_id');
    }

    public function bed(): BelongsTo
    {
        return $this->belongsTo(Bed::class, 'bed_id');
    }

    public static function generateUniquePatientId()
    {
        $patientAdmissionId = Str::random(8);
        while (true) {
            $isExist = self::wherePatientAdmissionId($patientAdmissionId)->exists();
            if ($isExist) {
                self::generateUniquePatientId();
            }
            break;
        }

        return $patientAdmissionId;
    }

    public function prepareData()
    {
        return [
            'bed_name' => $this->bed->name ?? __('messages.common.n/a'),
            'patient' => $this->patient->patientUser->full_name ?? __('messages.common.n/a'),
            'phone' => $this->patient->patientUser->phone ?? __('messages.common.n/a'),
            'admission_date' => date('jS M, Y h:i A', strtotime($this->assign_date)) ?? __('messages.common.n/a'),
            'gender' => $this->patient->patientUser->gender ? 'Female' : 'Male' ?? __('messages.common.n/a'),
        ];
    }

    public function preparePatientAdmissionData()
    {
        return [
            'id' => $this->id,
            'patient_name' => $this->patient->patientUser->full_name ?? __('messages.common.n/a'),
            'admission_id' => $this->patient_admission_id ?? __('messages.common.n/a'),
            'patient_image' => $this->patient->patientUser->getApiImageUrlAttribute() ?? __('messages.common.n/a'),
            'admission_date' => date('jS M, Y', strtotime($this->admission_date)) ?? __('messages.common.n/a'),
            'admission_time' => date('h:i A', strtotime($this->admission_date)) ?? __('messages.common.n/a'),
        ];
    }

    public function prepareAdmission()
    {
        return [
            'id' => $this->id ?? __('messages.common.n/a'),
            'patient_admission_id' => $this->patient_admission_id ?? __('messages.common.n/a'),
            'doctor_id' => $this->doctor->id ?? __('messages.common.n/a'),
            'doctor_name' => $this->doctor->doctorUser->full_name ?? __('messages.common.n/a'),
            'doctor_image' => $this->doctor->doctorUser->getApiImageUrlAttribute(),
            'admission_date' => isset($this->admission_date) ? Carbon::parse($this->admission_date)->format('jS M, y') : __('messages.common.n/a'),
            'admission_time' => isset($this->admission_date) ? Carbon::parse($this->admission_date)->format('h:i A') : __('messages.common.n/a'),
            'discharge_date' => isset($this->discharge_date) ? Carbon::parse($this->discharge_date)->format('jS M, y') : __('messages.common.n/a'),
            'discharge_time' => isset($this->discharge_date) ? Carbon::parse($this->discharge_date)->format('h:i A') : __('messages.common.n/a'),
            'bed_id' => $this->bed->bed_id ?? __('messages.common.n/a'),
            'guardian_name' => $this->guardian_name ?? __('messages.common.n/a'),
            'guardian_relation' => $this->guardian_relation ?? __('messages.common.n/a'),
            'guardian_contact' => $this->guardian_contact ?? __('messages.common.n/a'),
            'guardian_address' => $this->guardian_address ?? __('messages.common.n/a'),
            'created_on' => $this->created_at->diffForHumans() ?? __('messages.common.n/a'),
            'insurance_detail' => [
                'package_name' => $this->package->name ?? __('messages.common.n/a'),
                'insurance_name' => $this->insurance->name ?? __('messages.common.n/a'),
                'agent_name' => $this->agent_name ?? __('messages.common.n/a'),
                'policy_no' => $this->policy_no ?? __('messages.common.n/a'),
            ],
        ];
    }
}
