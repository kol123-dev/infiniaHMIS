<?php

namespace App\Http\Requests;

use App\Models\InvestigationReport;
use Illuminate\Foundation\Http\FormRequest;

class CreateInvestigationReportRequest extends FormRequest
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
