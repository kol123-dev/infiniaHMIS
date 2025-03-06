<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UpdateUserPasswordRequest
 */
class ChangePasswordRequest extends FormRequest
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
     *
     * @return array The given data was invalid.
     */
    public function rules(): array
    {
        return [
            'password_current' => 'required',
            'password' => 'required|min:6|same:password_confirmation',
            'password_confirmation' => 'required|min:6',
        ];
    }

    public function messages(): array
    {
        return [
            'password.min' => __('messages.user.validate_min_character_password'),
            'password.same' => __('messages.user.validate_confirm_password'),
        ];
    }
}
