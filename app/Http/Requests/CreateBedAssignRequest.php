<?php

namespace App\Http\Requests;

use App\Models\BedAssign;
use Illuminate\Foundation\Http\FormRequest;

class CreateBedAssignRequest extends FormRequest
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
        return BedAssign::$rules;
    }

    /**
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'bed_id.required' => __('messages.ipd_patient.the_bed_field_is_required'),
            'ipd_patient_department_id' => __('messages.bed_assign.ipd_patient_department_field_required'),
        ];
    }
}
