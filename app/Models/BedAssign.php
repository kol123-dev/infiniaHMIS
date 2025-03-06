<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * Class BedAssign
 *
 * @version February 18, 2020, 6:49 am UTC
 *
 * @property int $id
 * @property int $patient_id
 * @property string $assign_date
 * @property string|null $discharge_date
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $patient
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BedAssign newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BedAssign newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BedAssign query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BedAssign whereAssignDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BedAssign whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BedAssign whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BedAssign whereDischargeDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BedAssign whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BedAssign wherePatientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BedAssign whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 *
 * @property string $case_id
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BedAssign whereCaseId($value)
 *
 * @property int $bed_id
 * @property-read \App\Models\Bed $bed
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BedAssign whereBedId($value)
 *
 * @property int $status
 * @property-read PatientCase $caseFromBedAssign
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BedAssign whereStatus($value)
 *
 * @property int|null $ipd_patient_department_id
 * @property-read IpdPatientDepartment|null $ipdPatient
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BedAssign whereIpdPatientDepartmentId($value)
 *
 * @property int $is_default
 *
 * @method static \Illuminate\Database\Eloquent\Builder|BedAssign whereIsDefault($value)
 */
class BedAssign extends Model
{
    public $table = 'bed_assigns';

    public $fillable = [
        'bed_id',
        'ipd_patient_department_id',
        'patient_id',
        'case_id',
        'assign_date',
        'discharge_date',
        'description',
        'status',
    ];

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

    protected $casts = [
        'id' => 'integer',
        'bed_id' => 'integer',
        'ipd_patient_department_id' => 'integer',
        'patient_id' => 'integer',
        'case_id' => 'string',
        'assign_date' => 'datetime',
        'discharge_date' => 'datetime',
        'description' => 'string',
        'status' => 'integer',
    ];

    public static $rules = [
        'bed_id' => 'required',
        'case_id' => 'nullable',
        'assign_date' => 'required',
        'discharge_date' => 'nullable|after:assign_date',
        'description' => 'nullable|string',
        'ipd_patient_department_id' => 'required',
    ];

    public function prepareData()
    {
        return [
            'id' => $this->id,
            'patient_name' => $this->patient->patientUser->full_name,
            'bed_id' => $this->bed_id,
            'bed' => $this->bed->name,
            'case_id' => $this->caseFromBedAssign->case_id ?? __('messages.common.n/a'),
            'assign_date' => isset($this->assign_date) ? Carbon::parse($this->assign_date)->format('jS M, Y') : __('messages.common.n/a'),
            'assign_time' => isset($this->assign_date) ? \Carbon\Carbon::parse($this->assign_date)->isoFormat('LT') : __('messages.common.n/a'),
            'patient_image' => $this->patient->patientUser->getApiImageUrlAttribute() ?? __('messages.common.n/a'),
        ];
    }

    public function dataForEdit()
    {
        return [
            'id' => $this->id,
            'case_id' => $this->case_id ?? __('messages.common.n/a'),
            'patient_name' => $this->patient->patientUser->full_name ?? __('messages.common.n/a'),
            'ipd_patient' => $this->ipdPatient->ipd_number ?? __('messages.common.n/a'),
            'bed' => $this->bed->name ?? __('messages.common.n/a'),
            'assign_date' => isset($this->assign_date) ? Carbon::parse($this->assign_date)->format('Y-m-d H:i:s') : __('messages.common.n/a'),
            'discharge_date' => isset($this->discharge_date) ? Carbon::parse($this->discharge_date)->format('Y-m-d H:i:s') : __('messages.common.n/a'),
            'description' => $this->description ?? __('messages.common.n/a'),
            'ipd_patient_department_id' => $this->ipd_patient_department_id ?? __('messages.common.n/a'),
        ];
    }

    public function prepareBedAssignData()
    {
        return [
            'bed' => $this->bed->name ?? __('messages.common.n/a'),
            'bed_type' => $this->bed->bedType->title ?? __('messages.common.n/a'),
            'bed_id' => $this->bed->bed_id ?? __('messages.common.n/a'),
            'charge' => $this->bed->charge ?? __('messages.common.n/a'),
            'is_available' => $this->bed->is_available,
            'created_on' => \Carbon\Carbon::parse($this->created_at)->diffForHumans() ?? __('messages.common.n/a'),
            'description' => $this->bed->description ?? __('messages.common.n/a'),
            'currency_symbol' => getCurrencySymbol() ?? __('messages.common.n/a'),
        ];
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function bed(): BelongsTo
    {
        return $this->belongsTo(Bed::class, 'bed_id');
    }

    public function caseFromBedAssign(): BelongsTo
    {
        return $this->belongsTo(PatientCase::class, 'case_id', 'case_id');
    }

    public function ipdPatient(): BelongsTo
    {
        return $this->belongsTo(IpdPatientDepartment::class, 'ipd_patient_department_id');
    }
}
