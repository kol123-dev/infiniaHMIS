<?php

namespace App\Http\Requests;

use App\Models\PatientDiagnosisTest;
use Illuminate\Foundation\Http\FormRequest;

class CreatePatientDiagnosisTestRequest extends FormRequest
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
        return PatientDiagnosisTest::$rules;
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
