<?php

namespace App\Repositories;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Medicine;
use App\Models\Prescription;

/**
 * Class MedicineRepository
 *
 * @version February 12, 2020, 11:00 am UTC
 */
class MedicineRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'name',
        'selling_price',
        'buying_price',
        'generic_name',
        'batch_no',
        'effect',
        'betch_no',
        'qty',
        'mfg_date',
    ];

    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    public function model()
    {
        return Medicine::class;
    }

    public function getSyncList()
    {
        $data['categories'] = Category::all()->where('is_active', '=', 1)->pluck('name', 'id')->toArray();
        $data['brands'] = Brand::all()->pluck('name', 'id')->toArray();

        return $data;
    }

    public function getMedicineList()
    {
        $result = Medicine::all()->pluck('name', 'id')->toArray();

        $medicines = [];
        foreach ($result as $key => $item) {
            $medicines[] = [
                'key' => $key,
                'value' => $item,
            ];
        }

        return $medicines;
    }

    public function getMealList()
    {
        $result = Prescription::MEAL_ARR;

        $meal = [];
        foreach ($result as $key => $item) {
            $meal[] = [
                'key' => $key,
                'value' => $item,
            ];
        }

        return $meal;
    }

    public function getDoseDurationList()
    {
        $result = Prescription::DOSE_DURATION;

        $doseDuration = [];
        foreach ($result as $key => $item) {
            $doseDuration[] = [
                'key' => $key,
                'value' => $item,
            ];
        }

        return $doseDuration;
    }

    public function getDoseIntervalList()
    {
        $result = Prescription::DOSE_INTERVAL;

        $doseInterval = [];
        foreach ($result as $key => $item) {
            $doseInterval[] = [
                'key' => $key,
                'value' => $item,
            ];
        }

        return $doseInterval;
    }
}
