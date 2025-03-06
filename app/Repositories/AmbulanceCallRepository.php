<?php

namespace App\Repositories;

use App\Models\Ambulance;
use App\Models\AmbulanceCall;

/**
 * Class AmbulanceCallRepository
 *
 * @version March 26, 2020, 7:06 am UTC
 */
class AmbulanceCallRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'ambulance_id',
        'patient_id',
        'driver_name',
        'date',
        'amount',
    ];

    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    public function model()
    {
        return AmbulanceCall::class;
    }

    public function update($input, $ambulanceCall)
    {
        $ambulanceId = $ambulanceCall->ambulance_id;
        $ambulanceCall->update($input);

        if ($input['ambulance_id'] == $ambulanceId) {
            return true;
        }
        
        Ambulance::where('id', $ambulanceId)->update(['is_available' => true]);
        Ambulance::where('id', $input['ambulance_id'])->update(['is_available' => false]);

        return true;
    }
}
