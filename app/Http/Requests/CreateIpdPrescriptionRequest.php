<?php

namespace App\Http\Requests;

use App\Models\IpdPrescription;
use Illuminate\Foundation\Http\FormRequest;

class CreateIpdPrescriptionRequest extends FormRequest
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
        return IpdPrescription::$rules;
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
