<?php

namespace App\Repositories;

use App\Models\ChargeCategory;
use App\Models\PathologyCategory;
use App\Models\PathologyTest;
use App\Models\PathologyParameter;
use App\Models\PathologyParameterItem;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Exception;

/**
 * Class PathologyTestRepository
 *
 * @version April 14, 2020, 9:33 am UTC
 */
class PathologyTestRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'test_name',
        'short_name',
        'test_type',
        'category_id',
        'unit',
        'subcategory',
        'method',
        'report_days',
        'charge_category_id',
        'standard_charge',
    ];

    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    public function model()
    {
        return PathologyTest::class;
    }

    public function store($input){
        try {
            DB::beginTransaction();

            $pathologyTest = PathologyTest::create([
                'test_name' => $input['test_name'],
                'short_name' => $input['short_name'],
                'test_type' => $input['test_type'],
                'category_id' => $input['category_id'],
                'unit' => $input['unit'],
                'subcategory' => $input['subcategory'],
                'method' => $input['method'],
                'report_days' => $input['report_days'],
                'charge_category_id' => $input['charge_category_id'],
                'standard_charge' => $input['standard_charge'],
                'patient_id' => $input['patient_id'],
            ]);

            if ($input['parameter_id']) {
                foreach ($input['parameter_id'] as $key => $value) {
                    PathologyParameterItem::create([
                        'pathology_id' => $pathologyTest->id,
                        'patient_result' => $input['patient_result'][$key],
                        'parameter_id' => $input['parameter_id'][$key],
                    ]);
                }
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function update($input, $pathologyTest){
        try {
            DB::beginTransaction();

            $pathologyTest->update([
                'test_name' => $input['test_name'],
                'short_name' => $input['short_name'],
                'test_type' => $input['test_type'],
                'category_id' => $input['category_id'],
                'unit' => $input['unit'],
                'subcategory' => $input['subcategory'],
                'method' => $input['method'],
                'report_days' => $input['report_days'],
                'charge_category_id' => $input['charge_category_id'],
                'standard_charge' => $input['standard_charge'],
                'patient_id' => $input['patient_id'],
            ]);
            $pathologyTest->parameterItems()->delete();

            if ($input['parameter_id']) {
                foreach ($input['parameter_id'] as $key => $value) {
                    PathologyParameterItem::create([
                        'pathology_id' => $pathologyTest->id,
                        'patient_result' => $input['patient_result'][$key],
                        'parameter_id' => $input['parameter_id'][$key],
                    ]);
                }
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function getPathologyAssociatedData()
    {
        $data['pathologyCategories'] = PathologyCategory::all()->pluck('name', 'id');
        $data['chargeCategories'] = ChargeCategory::all()->pluck('name', 'id');
        $data['pathologyParameters'] = PathologyParameter::all()->pluck('parameter_name', 'id');

        return $data;
    }

    public function getParameterDataList()
    {
        $result = PathologyParameter::all()->pluck('parameter_name', 'id')->toArray();

        $parameters = [];
        foreach ($result as $key => $item) {
            $parameters[] = [
                'key' => $key,
                'value' => $item,
            ];
        }

        return $parameters;
    }

    public function getParameterItemData($id){
        $parameterItem = PathologyParameterItem::with('pathologyParameter.pathologyUnit')->wherePathologyId($id)->get();

        return $parameterItem;
    }
    public function getSettingList(): array
    {
        $settings = Setting::pluck('value', 'key')->toArray();

        return $settings;
    }
}
