<?php

namespace App\Http\Requests;

use App\Classes\ApiResponseHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class StorePurchaseOrderRequest extends FormRequest
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
            'number' => 'required|string|unique:purchase_orders',
            'company_id' => 'required|numeric|exists:companies,id',
            'user_id' => 'required|numeric|exists:users,id',
            'status' => 'required|string',
            'warehouse_id' => 'required|numeric|exists:warehouses,id',
            'provider_id' => 'required|numeric|exists:providers,id',
            'expected_delivery' => 'required|date',
            'amount' => 'required|numeric',
            'notes' => 'nullable|string',
        ];
    }
    public function failedValidation(Validator $validator)
    {
        ApiResponseHelper::validationError($validator);
    }
}