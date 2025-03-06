<?php

namespace App\Http\Requests;

use App\Models\IpdPatientDepartment;
use Illuminate\Foundation\Http\FormRequest;

class CreateIpdPatientDepartmentRequest extends FormRequest
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
        return IpdPatientDepartment::$rules;
    }

    public function messages(): array
    {
        return [
            'case_id.required' => __('messages.ipd_patient.the_case_field_is_required'),
            'bed_id.required' => __('messages.ipd_patient.the_bed_field_is_required'),
        ];
    }
}
