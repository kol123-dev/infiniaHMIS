<?php

namespace App\Http\Requests;

use App\Models\Ambulance;
use Illuminate\Foundation\Http\FormRequest;

class UpdateAmbulanceRequest extends FormRequest
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
        $rules = Ambulance::$rules;
        $rules['vehicle_number'] = 'required|unique:ambulances,vehicle_number,'.$this->route('ambulance')->id;

        return $rules;
    }
}
