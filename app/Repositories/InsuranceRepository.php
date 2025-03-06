<?php

namespace App\Repositories;

use App\Models\Insurance;
use App\Models\InsuranceDisease;
use Arr;
use Exception;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Validator;

/**
 * Class InsuranceRepository
 *
 * @version February 22, 2020, 9:01 am UTC
 */
class InsuranceRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'name',
        'service_tax',
        'insurance_no',
        'insurance_code',
    ];

    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    public function model()
    {
        return Insurance::class;
    }

    public function store($input)
    {
        $diseaseItemInputArray = Arr::only($input, ['disease_name', 'disease_charge']);

        $insurance = Insurance::create(Arr::except($input, ['disease_name', 'disease_charge']));

        $diseaseItemInput = $this->prepareInputForDiseaseItem($diseaseItemInputArray);

        foreach ($diseaseItemInput as $key => $data) {
            $data['insurance_id'] = $insurance->id;
            $validator = Validator::make($data, InsuranceDisease::$rules);

            if ($validator->fails()) {
                throw new UnprocessableEntityHttpException($validator->errors()->first());
            }
            $data['disease_charge'] = removeCommaFromNumbers($data['disease_charge']);
            $disease = InsuranceDisease::create($data);
        }

        return true;
    }

    public function prepareInputForDiseaseItem($input)
    {
        $items = [];
        foreach ($input as $key => $data) {
            foreach ($data as $index => $value) {
                $items[$index][$key] = $value;
            }
        }

        return $items;
    }

    public function getDisease()
    {
        $disease = InsuranceDisease::all();

        return $disease;
    }

    public function getInsuranceDisease($insuranceId)
    {
        $diseases = InsuranceDisease::whereInsuranceId($insuranceId)->get();

        return $diseases;
    }

    public function update($insurance, $input)
    {
        $diseaseItemInputArray = Arr::only($input, ['disease_name', 'disease_charge']);

        $insurance->update($input);

        $disease = InsuranceDisease::whereInsuranceId($insurance->id);
        $disease->delete();
        $diseaseItemInput = $this->prepareInputForDiseaseItem($diseaseItemInputArray);

        foreach ($diseaseItemInput as $key => $data) {
            $data['insurance_id'] = $insurance->id;
            $validator = Validator::make($data, InsuranceDisease::$rules);

            if ($validator->fails()) {
                throw new UnprocessableEntityHttpException($validator->errors()->first());
            }
            $data['disease_charge'] = removeCommaFromNumbers($data['disease_charge']);
            InsuranceDisease::create($data);
        }

        return true;
    }

    public function delete($insuranceId)
    {
        try {
            $insurance = Insurance::find($insuranceId);
            $insurance->delete();
            $insuranceDisease = InsuranceDisease::whereInsuranceId($insuranceId);
            $insuranceDisease->delete();

            return true;
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }
}
