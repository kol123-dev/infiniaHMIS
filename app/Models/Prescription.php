<?php

namespace App\Models;

use App\Repositories\PrescriptionRepository;
use \PDF;
use Eloquent as Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

/**
 * Class Prescription
 *
 * @version March 31, 2020, 12:22 pm UTC
 *
 * @property int patient_id
 * @property string food_allergies
 * @property string tendency_bleed
 * @property string heart_disease
 * @property string high_blood_pressure
 * @property string diabetic
 * @property string surgery
 * @property string accident
 * @property string others
 * @property string medical_history
 * @property string current_medication
 * @property string female_pregnancy
 * @property string breast_feeding
 * @property string health_insurance
 * @property string low_income
 * @property string reference
 * @property bool status
 * @property int $id
 * @property int|null $doctor_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Patient $patient
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Prescription newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Prescription newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Prescription query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Prescription whereAccident($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Prescription whereBreastFeeding($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Prescription whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Prescription whereCurrentMedication($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Prescription whereDiabetic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Prescription whereDoctorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Prescription whereFemalePregnancy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Prescription whereFoodAllergies($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Prescription whereHealthInsurance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Prescription whereHeartDisease($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Prescription whereHighBloodPressure($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Prescription whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Prescription whereLowIncome($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Prescription whereMedicalHistory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Prescription whereOthers($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Prescription wherePatientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Prescription whereReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Prescription whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Prescription whereSurgery($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Prescription whereTendencyBleed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Prescription whereUpdatedAt($value)
 *
 * @mixin Model
 *
 * @property int $is_default
 * @property-read \App\Models\Doctor|null $doctor
 * * @property-read Collection|PrescriptionMedicineModal[] $getMedicine
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Prescription whereIsDefault($value)
 *
 * @property string|null $plus_rate
 * @property string|null $temperature
 * @property string|null $problem_description
 * @property string|null $test
 * @property string|null $advice
 * @property string|null $next_visit_qty
 * @property string|null $next_visit_time
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Prescription whereAdvice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Prescription whereNextVisitQty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Prescription whereNextVisitTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Prescription wherePlusRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Prescription whereProblemDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Prescription whereTemperature($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Prescription whereTest($value)
 */
class Prescription extends Model
{
    public $table = 'prescriptions';

    public $fillable = [
        'id',
        'patient_id',
        'doctor_id',
        'food_allergies',
        'tendency_bleed',
        'heart_disease',
        'high_blood_pressure',
        'diabetic',
        'surgery',
        'accident',
        'others',
        'medical_history',
        'current_medication',
        'female_pregnancy',
        'breast_feeding',
        'health_insurance',
        'low_income',
        'reference',
        'status',
        'plus_rate',
        'temperature',
        'problem_description',
        'test',
        'advice',
        'next_visit_qty',
        'next_visit_time',
    ];

    protected $casts = [
        'id' => 'integer',
        'patient_id' => 'integer',
        'food_allergies' => 'string',
        'tendency_bleed' => 'string',
        'heart_disease' => 'string',
        'high_blood_pressure' => 'string',
        'diabetic' => 'string',
        'surgery' => 'string',
        'accident' => 'string',
        'others' => 'string',
        'medical_history' => 'string',
        'current_medication' => 'string',
        'female_pregnancy' => 'string',
        'breast_feeding' => 'string',
        'health_insurance' => 'string',
        'low_income' => 'string',
        'reference' => 'string',
        'status' => 'boolean',
        'plus_rate' => 'string',
        'temperature' => 'string',
        'problem_description' => 'string',
        'test' => 'string',
        'advice' => 'string',
        'next_visit_qty' => 'string',
        'next_visit_time' => 'string',
    ];

    public static $rules = [
        'patient_id' => 'required',
    ];

    const STATUS_ALL = 2;

    const ACTIVE = 1;

    const INACTIVE = 0;

    const STATUS_ARR = [
        self::STATUS_ALL => 'All',
        self::ACTIVE => 'Active',
        self::INACTIVE => 'Deactive',
    ];

    const DAYS = 0;

    const MONTH = 1;

    const YEAR = 2;

    const TIME_ARR = [
        self::DAYS => 'Days',
        self::MONTH => 'Month',
        self::YEAR => 'Years',
    ];

    const AFETR_MEAL = 0;

    const BEFORE_MEAL = 1;

    const MEAL_ARR = [
        self::AFETR_MEAL => 'After Meal',
        self::BEFORE_MEAL => 'Before Meal',
    ];

    const ONE_TIME = 1;

    const TWO_TIME = 2;

    const THREE_TIME = 3;

    const FOUR_TIME = 4;

    const DOSE_INTERVAL = [
        self::ONE_TIME => 'Daily morning',
        self::TWO_TIME => 'Daily morning and evening',
        self::THREE_TIME => 'Daily morning, noon, and evening',
        self::FOUR_TIME => '4 times in a day',
    ];

    const ONE_DAY = 1;

    const THREE_DAY = 3;

    const ONE_WEEK = 7;

    const TWO_WEEK = 14;

    const ONE_MONTH = 30;

    const DOSE_DURATION = [
        self::ONE_DAY => 'Only one day',
        self::THREE_DAY => 'Upto Three days',
        self::ONE_WEEK => 'Upto One week',
        self::TWO_WEEK => 'Upto two week',
        self::ONE_MONTH => 'Upto one month',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }

    public function getMedicine()
    {
        return $this->hasMany(PrescriptionMedicineModal::class);
    }

    public function preparePrescription()
    {
        return [
            'id' => $this->id ?? __('messages.common.n/a'),
            'doctor_name' => $this->doctor->doctorUser->full_name ?? __('messages.common.n/a'),
            'doctor_image' => $this->doctor->doctorUser->getApiImageUrlAttribute() ?? __('messages.common.n/a'),
            'created_date' => Carbon::parse($this->created_at)->format('jS M, Y') ?? __('messages.common.n/a'),
            'created_time' => Carbon::parse($this->created_at)->format('h:i A') ?? __('messages.common.n/a'),
        ];
    }

    public function prepareDoctorPrescription()
    {
        return [
            'id' => $this->id,
            'patient_name' => $this->patient->patientUser->full_name ?? __('messages.common.n/a'),
            'patient_image' => $this->patient->patientUser->getApiImageUrlAttribute() ?? __('messages.common.n/a'),
            'created_date' => Carbon::parse($this->created_at)->format('jS M, Y') ?? __('messages.common.n/a'),
        ];
    }

    public function prepareDoctorPrescriptionDetailData()
    {
        return [
            'id' => $this->id,
            'doctor_id' => $this->doctor->id ?? __('messages.common.n/a'),
            'doctor_name' => $this->doctor->doctorUser->full_name ?? __('messages.common.n/a'),
            'specialist' => $this->doctor->specialist ?? __('messages.common.n/a'),
            'patient_name' => $this->patient->patientUser->full_name ?? __('messages.common.n/a'),
            'patient_age' => Carbon::parse($this->patient->patientUser->dob)->age ?? __('messages.common.n/a'),
            'created_date' => Carbon::parse($this->created_at)->format('jS M, Y') ?? __('messages.common.n/a'),
            'created_time' => Carbon::parse($this->created_at)->format('h:i A') ?? __('messages.common.n/a'),
            'problem' => $this->problem_description ?? __('messages.common.n/a'),
            'test' => $this->test ?? __('messages.common.n/a'),
            'advice' => $this->advice ?? __('messages.common.n/a'),
            'medicine' => $this->prepareMedicine($this->getMedicine) ?? __('messages.common.n/a'),
            'download_prescription' => $this->convertToPdf($this->id),
        ];
    }

    public function preparePatientPrescriptionDetailData()
    {
        return [
            'doctor_name' => $this->doctor->doctorUser->full_name ?? __('messages.common.n/a'),
            'specialist' => $this->doctor->specialist ?? __('messages.common.n/a'),
            'problem' => $this->problem_description ?? __('messages.common.n/a'),
            'test' => $this->test ?? __('messages.common.n/a'),
            'advice' => $this->advice ?? __('messages.common.n/a'),
            'medicine' => $this->prepareMedicine($this->getMedicine) ?? __('messages.common.n/a'),
            'download_prescription' => $this->convertToPdf($this->id),
        ];
    }

    public function convertToPdf($id)
    {
        $prescription['prescription'] = Prescription::with(['doctor', 'patient', 'getMedicine'])->find($id);
        $data = App()->make(prescriptionRepository::class)->getSyncListForCreate($id);
        $medicines = [];
        foreach ($prescription['prescription']->getMedicine as $medicine) {
            $data['medicine'] = Medicine::where('id', $medicine->medicine)->get();
            array_push($medicines, $data['medicine']);
        }
        if (Storage::exists('prescriptions/Prescription-'.$prescription['prescription']->id.'.pdf')) {
            Storage::delete('prescriptions/Prescription-'.$prescription['prescription']->id.'.pdf');
        }
        $pdf = PDF::loadView('prescriptions.prescription_pdf', compact('prescription', 'medicines', 'data'));
        Storage::disk(config('app.media_disc'))->put('prescriptions/Prescription-'.$prescription['prescription']->id.'.pdf',
            $pdf->output());
        $url = Storage::disk(config('app.media_disc'))->url('prescriptions/Prescription-'.$prescription['prescription']->id.'.pdf');

        return $url ?? __('messages.common.n/a');
    }

    public function prepareMedicine($getMedicine)
    {
        $data = [];
        if (! empty($getMedicine)) {
            foreach ($getMedicine as $medicine) {
                $medicineData = Medicine::find($medicine->medicine);
                $data[] = [
                    'name' => $medicineData->name ?? __('messages.common.n/a'),
                    'dosage' => $medicine->dosage ?? __('messages.common.n/a'),
                    'days' => $medicine->day.' '.'day' ?? __('messages.common.n/a'),
                    'time' => $medicine->time == 0 ? __('messages.prescription.after_meal') : __('messages.prescription.before_meal') ?? __('messages.common.n/a'),
                ];
            }

            return $data;
        }
    }
}
