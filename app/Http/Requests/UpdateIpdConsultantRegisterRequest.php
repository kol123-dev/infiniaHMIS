<?php

namespace App\Http\Requests;

use App\Models\IpdConsultantRegister;
use Illuminate\Foundation\Http\FormRequest;

class UpdateIpdConsultantRegisterRequest extends FormRequest
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
        return IpdConsultantRegister::$rules;
    }

    public function messages()
    {
        return [
            'applied_date.required' => __('messages.select_applied_date'),
            'instruction_date.required' => __('messages.select_instruction_date'),
            'instruction.*.required'    => 'The instruction field is required'

        ];
    }
}
