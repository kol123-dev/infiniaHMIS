<?php

namespace App\Http\Requests;

use App\Models\AddCustomFields;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateAddCustomFieldRequest extends FormRequest
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
        $rules = AddCustomFields::$rules;
        $fieldId = $this->route('add_custom_field');

        $rules['field_name'] = [
            'required',
            Rule::unique('add_custom_fields')
                ->where('module_name', $this->module_name)
                ->ignore($fieldId),
        ];
        return $rules;
    }
}
