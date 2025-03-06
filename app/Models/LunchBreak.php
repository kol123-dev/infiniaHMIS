<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\LunchBreak
 *
 * @property int $id
 * @property int $doctor_id
 * @property string $break_from
 * @property string $break_to
 * @property int|null $every_day
 * @property string $date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Doctor $doctor
 * @method static \Illuminate\Database\Eloquent\Builder|LunchBreak newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LunchBreak newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LunchBreak query()
 * @method static \Illuminate\Database\Eloquent\Builder|LunchBreak whereBreakFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LunchBreak whereBreakTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LunchBreak whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LunchBreak whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LunchBreak whereDoctorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LunchBreak whereEveryDay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LunchBreak whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LunchBreak whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class LunchBreak extends Model
{
    use HasFactory;

    public $table = 'lunch_breaks';

    public $fillable = [
        'doctor_id',
        'break_from',
        'break_to',
        'every_day',
        'date',
    ];

    public static $rules = [
        'doctor_id' => 'required',
        'break_from' => 'required',
        'break_to' => 'required',
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }
}
