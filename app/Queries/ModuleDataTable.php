<?php

namespace App\Queries;

use App\Models\Module;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class ModuleDataTable.
 */
class ModuleDataTable
{
    /**
     * @return Module|Builder
     */
    public function get(array $input = [])
    {
        /** @var Module $query */
        $query = Module::Query();

        $query->when(isset($input['status']) && $input['status'] != Module::STATUS_ALL,
            function (Builder $query) use ($input) {
                $query->where('is_active', '=', $input['status']);
            });

        return $query;
    }
}
