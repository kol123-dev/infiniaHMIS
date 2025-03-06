<?php

namespace App\Http\Requests;

use App\Models\FrontService;
use Illuminate\Foundation\Http\FormRequest;

class UpdateFrontServiceRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $rules = FrontService::$rules;
        unset($rules['icon']);
        $rules['icon'] = 'mimes:jpeg,jpg,png,gif,pdf,docx,mp3,mp4,webp';

        return $rules;
    }
}
