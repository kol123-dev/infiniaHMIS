<?php

namespace App\Http\Requests;

use App\Models\BirthReport;
use Illuminate\Foundation\Http\FormRequest;

class UpdateBirthReportRequest extends FormRequest
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
        $rules = BirthReport::$rules;
        $rules['case_id'] = 'required|unique:birth_reports,case_id,'.$this->route('birthReport')->id;

        return $rules;
    }
}
