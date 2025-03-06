<?php

namespace App\Queries;

use App\Models\BloodDonor;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class BloodDonorDataTable
 */
class BloodDonorDataTable
{
    public function get(): Builder
    {
        $query = BloodDonor::query();

        return $query;
    }
}
