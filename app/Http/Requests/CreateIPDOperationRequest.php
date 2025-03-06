<?php

namespace App\Http\Requests;

use App\Models\IpdOperation;
use Illuminate\Foundation\Http\FormRequest;

class CreateIPDOperationRequest extends FormRequest
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
        return IpdOperation::$rules;
    }

    /**
     * @return array|string[]
     */
    public function messages(): array
    {
        return [
            'operation_category_id.required' => __('messages.operation_category.operation_category_field_required'),
            'operation_id.required' => __('messages.operation.operation_field_required'),
            'operation_date' => __('messages.operation.select_operation_date'),
            'doctor_id.required' => __('messages.doctor_department.doctor_field_required'),
        ];
    }
}
