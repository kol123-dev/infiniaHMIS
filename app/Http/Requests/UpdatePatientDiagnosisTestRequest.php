<?php

namespace App\Http\Requests;

use App\Models\PatientDiagnosisTest;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePatientDiagnosisTestRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = PatientDiagnosisTest::$rules;
        $rules['patient_id'] = 'required|unique:patient_diagnosis_tests,patient_id,'.$this->route('patientDiagnosisTest')->id;

        return $rules;
    }

    /***
     * @return array
     */
    public function messages(): array
    {
        return [
            'patient_id.unique' => __('messages.patient.patient_name_already_taken'),
        ];
    }
}
