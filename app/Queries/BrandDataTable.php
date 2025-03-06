<?php

namespace App\Queries;

use App\Models\Brand;
use Illuminate\Support\Collection;

/**
 * Class DepartmentsDataTable
 */
class BrandDataTable
{
    public function get(): Collection
    {
        $query = Brand::query();

        return $query;
    }
}
