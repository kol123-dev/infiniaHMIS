<?php

namespace App\Repositories;

use App\Models\DoctorHoliday;

/**
 * Class CityRepository
 *
 * @version July 31, 2021, 7:41 am UTC
 */
class HolidayRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'name',
        'doctor_id',
        'date',
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model()
    {
        return DoctorHoliday::class;
    }

    public function store($input)
    {
        $doctor_holiday = DoctorHoliday::where('doctor_id', $input['doctor_id'])->where('date',
            $input['date'])->exists();

        if (! $doctor_holiday) {
            DoctorHoliday::create($input);

            return true;
        } else {
            return false;
        }
    }
}
