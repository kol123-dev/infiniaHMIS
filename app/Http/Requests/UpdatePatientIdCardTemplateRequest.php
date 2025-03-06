<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\PatientIdCardTemplate;

class UpdatePatientIdCardTemplateRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = PatientIdCardTemplate::$rules;
        $rules['name'] = 'unique:patient_id_card_templates,name,'.$this->route('smart_patient_card');

        return $rules;
    }
}
