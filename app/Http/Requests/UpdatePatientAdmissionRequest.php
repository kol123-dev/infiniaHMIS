<?php

namespace App\Http\Requests;

use App\Models\PatientAdmission;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePatientAdmissionRequest extends FormRequest
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
        $rules = PatientAdmission::$rules;

        return $rules;
    }


}
