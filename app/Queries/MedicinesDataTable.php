<?php

namespace App\Queries;

use App\Models\Medicine;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class DepartmentsDataTable
 */
class MedicinesDataTable
{
    public function get(): Builder
    {
        return Medicine::with('brand')->select('medicines.*');
    }
}
