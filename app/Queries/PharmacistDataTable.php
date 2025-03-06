<?php

namespace App\Queries;

use App\Models\Pharmacist;
use Illuminate\Database\Query\Builder;

/**
 * Class CategoryDataTable.
 */
class PharmacistDataTable
{
    /**
     * @return Pharmacist|Builder
     */
    public function get(array $input = [])
    {
        /** @var Pharmacist $query */
        $query = Pharmacist::whereHas('user')->with('user.media')->select('pharmacists.*');

        $query->when(isset($input['status']) && $input['status'] != Pharmacist::STATUS_ALL,
            function (\Illuminate\Database\Eloquent\Builder $q) use ($input) {
                $q->whereHas('user', function ($q) use ($input) {
                    $q->where('status', '=', $input['status']);
                });
            });

        return $query;
    }
}
