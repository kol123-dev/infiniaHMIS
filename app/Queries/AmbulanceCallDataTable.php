<?php

namespace App\Queries;

use App\Models\AmbulanceCall;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class AmbulanceCallDataTable
 */
class AmbulanceCallDataTable
{
    public function get(): Builder
    {
        $query = AmbulanceCall::whereHas('patient.user')->with(['patient.user', 'ambulance']);

        return $query->select('ambulance_calls.*');
    }
}
