<?php

namespace App\Http\Requests;

use App\Models\Department;
use Illuminate\Foundation\Http\FormRequest;

class UpdateDepartmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->sanitize();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = Department::$rules;

        return $rules;
    }

    public function sanitize()
    {
        $input = $this->all();
        $input['is_active'] = (isset($input['is_active']) && ! empty($input['is_active'])) ? 1 : 0;

        $this->replace($input);
    }
}
