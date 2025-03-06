<?php

namespace App\Http\Requests;

use App\Models\Operation;
use Illuminate\Foundation\Http\FormRequest;

class CreateOperationRequest extends FormRequest
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
        return Operation::$rules;
    }

    public function messages(): array
    {
        return [
            'operation_category_id.required' => __('messages.operation_category.operation_category_field_required'),
        ];
    }
}
