<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateWebAppointmentRequest extends FormRequest
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
        $validationFields = [
            'doctor_id' => 'required',
            'department_id' => 'required',
            'opd_date' => 'required',
            'problem' => 'nullable',
            'email' => 'required|email:filter',
        ];
        if (request()->get('patient_type') == 1) {
            $validationFields['password'] = 'required|same:password_confirmation|min:6';
        }
        if (request()->get('patient_type') == 2) {
            $validationFields['patient_id'] = 'required';
        }

        return $validationFields;
    }
}
