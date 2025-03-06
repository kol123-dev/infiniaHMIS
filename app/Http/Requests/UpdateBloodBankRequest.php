<?php

namespace App\Http\Requests;

use App\Models\BloodBank;
use Illuminate\Foundation\Http\FormRequest;

class UpdateBloodBankRequest extends FormRequest
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
        $rules = BloodBank::$rules;
        $rules['blood_group'] = $rules['blood_group'].','.$this->route('bloodBank')->id;

        return $rules;
    }
}
