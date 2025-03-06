<?php

namespace App\Queries;

use App\Models\IpdPayment;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class IpdPaymentDataTable
 */
class IpdPaymentDataTable
{
    public function get(int $ipdPatientDepartmentId): Builder
    {
        return IpdPayment::whereIpdPatientDepartmentId($ipdPatientDepartmentId)->select('ipd_payments.*');
    }
}
