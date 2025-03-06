<?php

namespace App\Http\Requests;

use App\Models\AmbulanceCall;
use Illuminate\Foundation\Http\FormRequest;

class UpdateAmbulanceCallRequest extends FormRequest
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
        $rules = AmbulanceCall::$rules;

        return $rules;
    }

    public function messages()
    {
        return [
            'min' => __('messages.patient.select_one_patient'),
        ];
    }
}
