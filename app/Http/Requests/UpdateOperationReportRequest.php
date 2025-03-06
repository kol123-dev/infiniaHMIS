<?php

namespace App\Http\Requests;

use App\Models\OperationReport;
use Illuminate\Foundation\Http\FormRequest;

class UpdateOperationReportRequest extends FormRequest
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
        $rules = OperationReport::$rules;
        $rules['case_id'] = 'required|unique:operation_reports,case_id,'.$this->route('operationReport')->id;

        return $rules;
    }


}
