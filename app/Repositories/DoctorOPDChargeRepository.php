<?php

namespace App\Repositories;

use App\Models\Doctor;
use App\Models\DoctorOPDCharge;

class DoctorOPDChargeRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'doctor_id',
        'standard_charge',
    ];

    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    public function model()
    {
        return DoctorOPDCharge::class;
    }

    public function getDoctors()
    {
        $doctors = Doctor::with('doctorUser')->get()->where('doctorUser.status', '=', 1)->pluck('doctorUser.full_name', 'id')->sort();

        return $doctors;
    }
}
