<?php

namespace App\Http\Requests;

use App\Models\IpdCharge;
use App\Models\IpdPatientDepartment;
use App\Models\IpdPayment;
use Illuminate\Foundation\Http\FormRequest;

class UpdateIpdPaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $amount = $this->request->get('amount');
        $this->request->set('amount', removeCommaFromNumbers($amount));
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = IpdPayment::$rules;

        $ipdPatienId = $this->request->get('ipd_patient_department_id');
        // get tottal charges
        $totalCharges = IpdCharge::whereIpdPatientDepartmentId($ipdPatienId)->get()->sum('applied_charge');
        $bedDetails = IpdPatientDepartment::with('bed')->find($ipdPatienId);
        // get old payment total excepet current amount
        $totalAmount = IpdPayment::whereIpdPatientDepartmentId($ipdPatienId)
            ->where('id', '!=', $this->id)->get()->sum('amount');
        $totalCharges += $bedDetails->bed->charge;
        $totalCharges = $totalCharges - $totalAmount;
        $totalCharges = round((float)$totalCharges, 2);
        $rules['amount'] = "required|numeric|between:1,$totalCharges";

        return $rules;
    }
}
