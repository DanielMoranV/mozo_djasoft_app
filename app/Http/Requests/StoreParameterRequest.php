<?php

namespace App\Http\Requests;

use App\Classes\ApiResponseHelper;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class StoreParameterRequest extends FormRequest
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
            'warehouse_id' => 'required|numeric|exists:warehouses,id',
            'user_id' => 'required|numeric|exists:users,id',
            'company_id' => 'required|numeric|exists:companies,id',
        ];
    }
    public function failedValidation(Validator $validator)
    {
        ApiResponseHelper::validationError($validator);
    }
}
