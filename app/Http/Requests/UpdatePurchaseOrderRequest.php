<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use App\Classes\ApiResponseHelper;

class UpdatePurchaseOrderRequest extends FormRequest
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
            'number' => 'nullable|string|unique:purchase_orders',
            'company_id' => 'nullable|numeric|exists:companies,id',
            'user_id' => 'nullable|numeric|exists:users,id',
            'status' => 'nullable|string',
            'warehouse_id' => 'nullable|numeric|exists:warehouses,id',
            'provider_id' => 'nullable|numeric|exists:providers,id',
            'expected_delivery' => 'nullable|date',
            'amount' => 'nullable|numeric',
            'notes' => 'nullable|string',
        ];
    }
    public function failedValidation(Validator $validator)
    {
        ApiResponseHelper::validationError($validator);
    }
}