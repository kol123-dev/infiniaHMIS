<?php

namespace App\Http\Requests;

use App\Models\Charge;
use Illuminate\Foundation\Http\FormRequest;

class UpdateChargeRequest extends FormRequest
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
        $rules = Charge::$rules;
        $rules['code'] = 'required|unique:charges,code,'.$this->route('charge')->id;

        return $rules;
    }
}
