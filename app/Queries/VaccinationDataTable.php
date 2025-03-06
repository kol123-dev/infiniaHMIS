<?php

namespace App\Queries;

use App\Models\Vaccination;
use Illuminate\Database\Query\Builder;

/**
 * Class VaccinationDataTable
 */
class VaccinationDataTable
{
    public function get(): Builder
    {
        return Vaccination::select('vaccinations.*');
    }
}
