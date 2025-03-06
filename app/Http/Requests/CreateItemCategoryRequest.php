<?php

namespace App\Http\Requests;

use App\Models\ItemCategory;
use Illuminate\Foundation\Http\FormRequest;

class CreateItemCategoryRequest extends FormRequest
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
        return ItemCategory::$rules;
    }
}
