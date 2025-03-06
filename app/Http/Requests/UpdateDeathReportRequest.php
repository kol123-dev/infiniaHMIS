<?php

namespace App\Http\Requests;

use App\Models\DeathReport;
use Illuminate\Foundation\Http\FormRequest;

class UpdateDeathReportRequest extends FormRequest
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
        $rules = DeathReport::$rules;
        $rules['case_id'] = 'required|unique:death_reports,case_id,'.$this->route('deathReport')->id;

        return $rules;
    }
}
