<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\ScheduleDay
 *
 * @property int $id
 * @property int $doctor_id
 * @property string $per_patient_time
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Doctor $doctor
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ScheduleDay newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ScheduleDay newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ScheduleDay query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ScheduleDay whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ScheduleDay whereDoctorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ScheduleDay whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ScheduleDay wherePerPatientTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ScheduleDay whereSerialVisibility($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ScheduleDay whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 *
 * @property int $schedule_id
 * @property string $available_on
 * @property string $available_from
 * @property string $available_to
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ScheduleDay whereAvailableFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ScheduleDay whereAvailableOn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ScheduleDay whereAvailableTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ScheduleDay whereScheduleId($value)
 *
 * @property-read Schedule $schedule
 */
class ScheduleDay extends Model
{
    public static $rules = [
        'doctor_id' => 'required',
        'available_on' => 'required',
        'available_from' => 'required',
        'available_to' => 'required',
    ];

    public $table = 'schedule_days';

    public $fillable = [
        'doctor_id',
        'schedule_id',
        'available_on',
        'available_from',
        'available_to',
    ];

    protected $casts = [
        'id' => 'integer',
        'doctor_id' => 'integer',
        'schedule_id' => 'integer',
        'available_on' => 'string',
    ];

    public function getAvailableFromAttribute()
    {
        return \Carbon\Carbon::parse($this->attributes['available_from'])->Format('H:i:s');
    }

    public function getAvailableToAttribute()
    {
        return \Carbon\Carbon::parse($this->attributes['available_to'])->Format('H:i:s');
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class, 'schedule_id');
    }

    public function prepareScheduleDay()
    {
        return [
            'available_on' => $this->available_on ?? __('messages.common.n/a'),
            'available_from' => $this->available_from ?? __('messages.common.n/a'),
            'available_to' => $this->available_to ?? __('messages.common.n/a'),
        ];
    }
}
