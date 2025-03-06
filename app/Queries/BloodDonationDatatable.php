<?php

namespace App\Queries;

use App\Models\BloodDonation;

/**
 * Class BloodDonationDatatable
 */
class BloodDonationDatatable
{
    public function get(): BloodDonation
    {
        /** @var BloodDonation $query */
        $query = BloodDonation::with(['blooddonor'])->select('blood_donations.*');

        return $query;
    }
}
