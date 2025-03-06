<?php

namespace App\Http\Requests;

use App\Models\Receptionist;
use Illuminate\Foundation\Http\FormRequest;

class CreateReceptionistRequest extends FormRequest
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
        $rules = Receptionist::$rules;
        $rules['image'] = 'mimes:jpeg,jpg,png,gif';

        return $rules;
    }

    /**
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'image.mimes' => __('messages.user.validate_image_type'),
        ];
    }
}
