<?php

namespace App\Http\Requests;

use App\Models\Invoice;
use Illuminate\Foundation\Http\FormRequest;

class UpdateInvoiceRequest extends FormRequest
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
        $rules = Invoice::$rules;

        return $rules;
    }

    public function messages(): array
    {
        return [
            'patient_id.required' => __('messages.patient.patient_field_required'),
            'invoice_date.required' => __('messages.invoice.invoice_date_field_required'),
        ];
    }
}
