<?php

namespace App\Queries;

use App\Models\InvestigationReport;
use App\Models\User;
use Auth;
use Illuminate\Database\Query\Builder;

/**
 * Class InvestigationReportDataTable.
 */
class InvestigationReportDataTable
{
    /**
     * @return InvestigationReport|Builder
     */
    public function get(array $input = [])
    {
        /** @var InvestigationReport $query */
        $query = InvestigationReport::whereHas('patient.user')->whereHas('doctor.user')->with('patient.user',
            'doctor.user', 'media')->select('investigation_reports.*');

        $query->when(isset($input['status']) && $input['status'] != InvestigationReport::STATUS_ALL,
            function (\Illuminate\Database\Eloquent\Builder $q) use ($input) {
                $q->where('status', '=', $input['status']);
            });

        /** @var User $user */
        $user = Auth::user();
        if ($user->hasRole('Doctor')) {
            $query->where('doctor_id', $user->owner_id);
        }

        return $query;
    }
}
