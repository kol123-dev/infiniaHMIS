<?php

namespace App\Http\Requests;

use App\Models\Vaccination;
use Illuminate\Foundation\Http\FormRequest;

class CreateVaccinationRequest extends FormRequest
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
        return Vaccination::$rules;
    }
}
