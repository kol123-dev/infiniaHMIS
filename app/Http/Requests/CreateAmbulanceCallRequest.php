<?php

namespace App\Http\Requests;

use App\Models\AmbulanceCall;
use Illuminate\Foundation\Http\FormRequest;

class CreateAmbulanceCallRequest extends FormRequest
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
        return AmbulanceCall::$rules;
    }

    public function messages()
    {
        return [
            'min' => __('messages.patient.select_one_patient'),
        ];
    }
}
