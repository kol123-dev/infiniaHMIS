<?php

namespace App\Http\Requests;

use App\Models\Insurance;
use Illuminate\Foundation\Http\FormRequest;

class UpdateInsuranceRequest extends FormRequest
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
        $rules = Insurance::$rules;
        $rules['name'] = 'required|unique:insurances,name,'.$this->route('insurance')->id;

        return $rules;
    }
}
