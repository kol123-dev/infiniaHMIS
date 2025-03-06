<?php

namespace App\Http\Requests;

use App\Models\ChargeCategory;
use Illuminate\Foundation\Http\FormRequest;

class UpdateChargeCategoryRequest extends FormRequest
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
        $rules = ChargeCategory::$rules;
        $rules['name'] = 'required|unique:charge_categories,name,'.$this->route('charge_category')->id;

        return $rules;
    }
}
