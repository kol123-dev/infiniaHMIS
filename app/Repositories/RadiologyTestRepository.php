<?php

namespace App\Repositories;

use App\Models\ChargeCategory;
use App\Models\RadiologyCategory;
use App\Models\RadiologyTest;

/**
 * Class RadiologyTestRepository
 *
 * @version April 13, 2020, 5:06 am UTC
 */
class RadiologyTestRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'testname',
        'shortname',
        'testtype',
        'category_id',
        'subcategory',
        'reportdays',
        'charge_category_id',
        'standard_charge',
    ];

    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    public function model()
    {
        return RadiologyTest::class;
    }

    public function getRadiologyAssociatedData()
    {
        $data['radiologyCategories'] = RadiologyCategory::all()->pluck('name', 'id')->sort();
        $data['chargeCategories'] = ChargeCategory::orderBy('name')->pluck('name', 'id');

        return $data;
    }
}
