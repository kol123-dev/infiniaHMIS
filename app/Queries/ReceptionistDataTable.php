<?php

namespace App\Queries;

use App\Models\Receptionist;
use Illuminate\Database\Query\Builder;

/**
 * Class ReceptionistDataTable
 */
class ReceptionistDataTable
{
    /**
     * @return Receptionist|Builder
     */
    public function get(array $input = [])
    {
        /** @var Receptionist $query */
        $query = Receptionist::whereHas('user')->with('user.media')->select('receptionists.*');

        $query->when(isset($input['status']) && $input['status'] != Receptionist::STATUS_ALL,
            function (\Illuminate\Database\Eloquent\Builder $q) use ($input) {
                $q->whereHas('user', function ($q) use ($input) {
                    $q->where('status', '=', $input['status']);
                });
            });

        return $query;
    }
}
