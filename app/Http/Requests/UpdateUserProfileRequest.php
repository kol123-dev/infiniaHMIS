<?php

namespace App\Http\Requests;

use App\Models\User;
use Auth;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserProfileRequest extends FormRequest
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
        $id = Auth::user()->id;
        $rules = [
            'first_name' => 'required',
            'last_name' => 'nullable',
            'email' => 'required|email|unique:users,email,'.$id.'|regex:/^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/',
            'phone' => 'required|nullable|numeric',
            'photo' => 'mimes:jpeg,jpg,png',
        ];

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
