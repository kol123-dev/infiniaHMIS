<?php

namespace App\Repositories;

use App\Models\DiagnosisCategory;
use App\Models\PatientDiagnosisProperty;
use App\Models\PatientDiagnosisTest;
use App\Models\Setting;
use Arr;
use Str;

class PatientDiagnosisTestRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'patient_id',
        'doctor_id',
        'category_id',
        'report_number',
    ];

    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    public function model()
    {
        return PatientDiagnosisTest::class;
    }

    public static function getUniqueReportNumber()
    {
        $reportNumber = mb_strtoupper(Str::random(8));
        while (true) {
            $isExist = PatientDiagnosisTest::whereReportNumber($reportNumber)->exists();
            if ($isExist) {
                self::getUniqueReportNumber();
            }
            break;
        }

        return $reportNumber;
    }

    public function getDiagnosisCategory()
    {
        $diagnosisCategories = DiagnosisCategory::all()->pluck('name', 'id')->sort();

        return $diagnosisCategories;
    }

    public function getPatientDiagnosisTestProperty($patientDiagnosisTestId)
    {
        $diagnosisTestProperty = PatientDiagnosisProperty::wherePatientDiagnosisId($patientDiagnosisTestId)->get();

        return $diagnosisTestProperty;
    }

    public function store($input)
    {
        $patientDiagnosisTest = PatientDiagnosisTest::create(Arr::only($input,
            ['patient_id', 'doctor_id', 'category_id', 'report_number']));

        $propertyInputArray = Arr::except($input,
            ['_token', 'patient_id', 'doctor_id', 'category_id', 'report_number', 'property_name', 'property_value']);

        foreach ($propertyInputArray as $key => $value) {
            PatientDiagnosisProperty::create([
                'patient_diagnosis_id' => $patientDiagnosisTest->id,
                'property_name' => $key,
                'property_value' => $value,
            ]);
        }

        if (isset($input['property_name']) && ! empty($input['property_name'])) {
            $otherProperty = Arr::only($input, ['property_name', 'property_value']);
            $patientDiagnosisTestItemInput = $this->prepareInputForPatientDiagnosisTest($otherProperty);

            foreach ($patientDiagnosisTestItemInput as $key => $data) {
                if (! empty($data['property_name'])) {
                    $data['patient_diagnosis_id'] = $patientDiagnosisTest->id;
                    PatientDiagnosisProperty::create($data);
                }
            }
        }

        return true;
    }

    public function updatePatientDiagnosis($input, $patientDiagnosisTest)
    {
        $patientDiagnosisTest->update(Arr::only($input,
            ['patient_id', 'doctor_id', 'category_id', 'report_number']));

        $diagnosisProperty = PatientDiagnosisProperty::wherePatientDiagnosisId($patientDiagnosisTest->id);
        $diagnosisProperty->delete();

        $propertyInputArray = Arr::except($input,
            ['_token', 'patient_id', 'doctor_id', 'category_id', 'report_number', 'property_name', 'property_value']);

        foreach ($propertyInputArray as $key => $value) {
            PatientDiagnosisProperty::create([
                'patient_diagnosis_id' => $patientDiagnosisTest->id,
                'property_name' => $key,
                'property_value' => $value,
            ]);
        }

        if (isset($input['property_name']) && ! empty($input['property_name'])) {
            $otherProperty = Arr::only($input, ['property_name', 'property_value']);
            $patientDiagnosisTestItemInput = $this->prepareInputForPatientDiagnosisTest($otherProperty);

            foreach ($patientDiagnosisTestItemInput as $key => $data) {
                if (! empty($data['property_name'])) {
                    $data['patient_diagnosis_id'] = $patientDiagnosisTest->id;
                    PatientDiagnosisProperty::create($data);
                }
            }
        }

        return true;
    }

    public function prepareInputForPatientDiagnosisTest($input)
    {
        $item = [];
        foreach ($input as $key => $data) {
            foreach ($data as $index => $value) {
                $item[$index][$key] = $value;
            }
        }

        return $item;
    }

    public function getSettingList()
    {
        $settings = Setting::pluck('value', 'key')->toArray();

        return $settings;
    }
}
