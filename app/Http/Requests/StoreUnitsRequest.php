<?php

namespace App\Http\Requests;

use App\Classes\ApiResponseHelper;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class StoreUnitsRequest extends FormRequest
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
        return [
            'units' => 'required|array',
            'units.*.name' => 'required|string|max:50',
            'units.*.symbol' => 'required|string|max:10'
        ];
    }

    public function failedValidation(Validator $validator)
    {
        ApiResponseHelper::validationError($validator);
    }
}
