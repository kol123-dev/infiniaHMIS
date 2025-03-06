<?php

namespace App\Http\Requests;

use App\Models\PathologyCategory;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePathologyCategoryRequest extends FormRequest
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
        $rules = PathologyCategory::$rules;
        $rules['name'] = 'required|unique:pathology_categories,name,'.$this->route('pathologyCategory')->id;

        return $rules;
    }


}
