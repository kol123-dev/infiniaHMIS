<?php

namespace App\Queries;

use App\Models\FrontService;

/**
 * Class FrontServiceDataTable
 */
class FrontServiceDataTable
{
    public function get(array $input = []): FrontService
    {
        /** @var FrontService $query */
        $query = FrontService::query()->select('front_services.*')->with('media');

        return $query;
    }
}
