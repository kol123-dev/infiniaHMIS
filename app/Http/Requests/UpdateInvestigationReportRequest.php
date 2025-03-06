<?php

namespace App\Http\Requests;

use App\Models\InvestigationReport;
use Illuminate\Foundation\Http\FormRequest;

class UpdateInvestigationReportRequest extends FormRequest
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
        $rules = InvestigationReport::$rules;
        $rules['patient_id'] = 'required|unique:investigation_reports,patient_id,'.$this->route('investigationReport')->id;
        $rules['attachment'] = 'mimes:jpeg,jpg,png,pdf,doc,docx';

        return $rules;
    }

    /**
     * @return array|string[]
     */
    public function messages(): array
    {
        return [
            'patient_id.unique' => __('messages.patient.patient_name_already_taken'),
            'attachment.mimes' => __('messages.document.validate_doc_type'),
        ];
    }
}
