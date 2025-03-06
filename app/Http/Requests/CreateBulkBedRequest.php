<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateBulkBedRequest extends FormRequest
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
        return [
            'name.*' => 'required|distinct|unique:beds,name',
            'bed_type.*' => 'required',
            //            'charge.*'   => 'required|numeric|min:0',
            'charge.*' => 'required|min:0|max:999999999',
        ];
    }

    public function messages(): array
    {
        return [
            'name.*.required' => __('messages.ipd_patient.the_bed_field_is_required'),
            'name.*.distinct' => __('messages.ipd_patient.the_bed_field_has_a_duplicate_value'),
            'name.*.unique' => __('messages.ipd_patient.the_bed_already_taken'),
            'bed_type.*' => __('messages.ipd_patient.the_bed_type_is_required'),
            'charge.*required' => __('messages.ipd_patient.charge_required'),
        ];
    }
}
