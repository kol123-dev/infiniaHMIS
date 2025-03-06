<?php

namespace App\Http\Requests;

use App\Models\PathologyUnit;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePathologyUnitRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $rules = PathologyUnit::$rules;
        $rules['name'] = 'required|unique:pathology_units,name,'.$this->route('pathologyUnit')->id;

        return $rules;
    }
}
