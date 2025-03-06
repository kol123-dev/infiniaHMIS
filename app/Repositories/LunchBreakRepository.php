<?php

namespace App\Repositories;

use App\Models\LunchBreak;
use Carbon\Carbon;

/**
 * Class CityRepository
 *
 * @version July 31, 2021, 7:41 am UTC
 */
class LunchBreakRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'doctor_id',
        'break_from',
        'break_to',
        'every_day',
        'date',
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model()
    {
        return LunchBreak::class;
    }

    public function store($input)
    {
        if(isset($input['date'])){
            $breaks = LunchBreak::whereDoctorId($input['doctor_id'])->where('date', $input['date'])->get();
        }else{
            $breaks = LunchBreak::whereDoctorId($input['doctor_id'])->where('every_day', 1)->get();
        }
        $doctor_break = false;

        foreach ($breaks as $break) {
            $from = Carbon::createFromTimeString($input['break_from'])->between($break->break_from,$break->break_to);
            $to = Carbon::createFromTimeString($input['break_to'])->between($break->break_from,$break->break_to);

            if($from && $to){
                $doctor_break = true;
                break;
            }
        }

        if (! $doctor_break) {
            LunchBreak::create([
                'doctor_id' => $input['doctor_id'],
                'break_from' => $input['break_from'],
                'break_to' => $input['break_to'],
                'date' => isset($input['date']) ? $input['date'] : null,
                'every_day' => isset($input['date']) ? null : 1,
            ]);

            return true;
        } else {
            return false;
        }
    }
}
