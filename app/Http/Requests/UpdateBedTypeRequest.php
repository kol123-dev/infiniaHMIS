<?php

namespace App\Http\Requests;

use App\Models\BedType;
use Illuminate\Foundation\Http\FormRequest;

class UpdateBedTypeRequest extends FormRequest
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
        $rules = BedType::$rules;
        $rules['title'] = 'required|unique:bed_types,title,'.$this->route('bedType')->id;

        return $rules;
    }
}
