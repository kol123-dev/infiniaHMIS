<?php

namespace App\Http\Requests;

use App\Models\OperationCategory;
use Illuminate\Foundation\Http\FormRequest;

class UpdateOperationCategoryRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $rules = OperationCategory::$rules;
        $rules['name'] = 'required|unique:operation_categories,name,'.$this->route('operationCategory')->id;

        return $rules;
    }


}
