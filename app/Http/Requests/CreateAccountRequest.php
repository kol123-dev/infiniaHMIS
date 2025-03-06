<?php

namespace App\Http\Requests;

use App\Models\Account;
use Illuminate\Foundation\Http\FormRequest;

class CreateAccountRequest extends FormRequest
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
        return Account::$rules;
    }

    protected function prepareForValidation()
    {
        $this->sanitize();
    }

    public function sanitize()
    {
        $input = $this->all();
        $input['status'] = ! empty($input['status']) ? 1 : 0;
        $this->replace($input);
    }
}
