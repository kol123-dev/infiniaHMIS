<?php

namespace App\Queries;

use App\Models\BedAssign;
use Illuminate\Database\Query\Builder;

/**
 * Class BedAssignDataTable
 */
class BedAssignDataTable
{
    /**
     * @return BedAssign|Builder
     */
    public function get(array $input = [])
    {
        /** @var BedAssign $query */
        $query = BedAssign::whereHas('patient.user')->with('patient.user', 'bed', 'caseFromBedAssign')
            ->select('bed_assigns.*');

        $query->when(isset($input['status']) && $input['status'] != BedAssign::STATUS_ALL,
            function (\Illuminate\Database\Eloquent\Builder $q) use ($input) {
                $q->where('status', '=', $input['status']);
            });

        return $query;
    }
}
