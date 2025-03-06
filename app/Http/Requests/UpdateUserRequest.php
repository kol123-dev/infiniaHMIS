<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UpdateUserRequest
 */
class UpdateUserRequest extends FormRequest
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
        $rules = User::$rules;
        $rules['password'] = '|same:password_confirmation|min:6';
        $rules['email'] = 'required|email:filter|unique:users,email,'.$this->route('user')->id;
        $rules['department_id'] = 'nullable';

        return $rules;
    }

    public function messages(): array
    {
        return [
            'phone.digits' => __('messages.user.phone_number_must_be_10_digits'),
            'email.regex' => __('messages.user.valid_email'),
            'photo.mimes' => __('messages.user.validate_image_type'),
        ];
    }
}
