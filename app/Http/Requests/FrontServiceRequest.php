<?php

namespace App\Http\Requests;

use App\Models\FrontService;
use Illuminate\Foundation\Http\FormRequest;

class FrontServiceRequest extends FormRequest
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
        $rules = FrontService::$rules;
        $rules['icon'] = 'required|mimes:jpg,jpeg,png';

        return $rules;
    }

    public function messages(): array
    {
        return [
            'icon.required' => __('messages.front_services.select_icon_file'),
            'icon.mimes' => __('messages.user.validate_image_type'),
        ];
    }
}
