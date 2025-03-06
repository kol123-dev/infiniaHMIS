<?php

namespace App\Http\Requests;

use App\Models\CallLog;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCallLogRequest extends FormRequest
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
        $rules = CallLog::$rules;

        return $rules;
    }
}
