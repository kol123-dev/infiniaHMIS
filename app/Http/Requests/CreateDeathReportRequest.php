<?php

namespace App\Http\Requests;

use App\Models\DeathReport;
use Illuminate\Foundation\Http\FormRequest;

class CreateDeathReportRequest extends FormRequest
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
        return DeathReport::$rules;
    }
}
