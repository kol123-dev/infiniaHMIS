<?php

namespace App\Http\Requests;

use App\Models\OpdPatientDepartment;
use Illuminate\Foundation\Http\FormRequest;

class CreateOpdPatientDepartmentRequest extends FormRequest
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
        return OpdPatientDepartment::$rules;
    }

    public function messages(): array
    {
        return [
            'case_id.required' => __('messages.ipd_patient.the_case_field_is_required'),
            'bed_id.required' => __('messages.ipd_patient.the_bed_field_is_required'),
        ];
    }
}
