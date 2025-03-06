<?php

namespace App\Http\Requests;

use App\Models\OpdPrescription;
use Illuminate\Foundation\Http\FormRequest;

class CreateOpdPrescriptionRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return OpdPrescription::$rules;
    }

    public function messages(): array
    {
        return [
        'category_id.*.required' => __('messages.ipd_patient_prescription.category_id').' '. __('messages.common.field_required'),
        'medicine_id.*.required' => __('messages.ipd_patient_prescription.medicine_id') .' '. __('messages.common.field_required'),
        'dosage.*.required' => __('messages.ipd_patient_prescription.dosage').' '. __('messages.common.field_required'),
        'instruction.*.required' => __('messages.ipd_patient_prescription.instruction').' '. __('messages.common.field_required'),
        'dose_interval.*.required' => __('messages.medicine_bills.dose_interval').' '. __('messages.common.field_required'),
        'day.*.required' => __('messages.purchase_medicine.dose_duration').' '. __('messages.common.field_required'),
        'time.*.required' => __('messages.prescription.time').' '. __('messages.common.field_required'),
        ];
    }
}
