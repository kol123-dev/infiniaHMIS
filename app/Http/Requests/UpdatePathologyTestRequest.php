<?php

namespace App\Http\Requests;

use App\Models\PathologyTest;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePathologyTestRequest extends FormRequest
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
        $rules = PathologyTest::$rules;
        $rules['test_name'] = 'required|unique:pathology_tests,test_name,'.$this->route('pathologyTest')->id;

        return $rules;
    }


}
