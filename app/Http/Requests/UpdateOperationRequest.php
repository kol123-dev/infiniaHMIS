<?php

namespace App\Http\Requests;

use App\Models\Operation;
use Illuminate\Foundation\Http\FormRequest;

class UpdateOperationRequest extends FormRequest
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
        $rules = Operation::$rules;
        $rules['name'] = $rules['name'].','.$this->route('id');

        return $rules;
    }

    public function messages(): array
    {
        return [
            'operation_category_id.required' => 'messages.operation_category.operation_category_field_required',
        ];
    }
}
