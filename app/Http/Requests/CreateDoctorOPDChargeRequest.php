<?php

namespace App\Http\Requests;

use App\Models\DoctorOPDCharge;
use Illuminate\Foundation\Http\FormRequest;

class CreateDoctorOPDChargeRequest extends FormRequest
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
        return DoctorOPDCharge::$rules;
    }

    public function messages(): array
    {
        return [
            'required.unique' => __('messages.doctor.doctor_name_already_taken'),
        ];
    }
}
