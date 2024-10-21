<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Classes\ApiResponseHelper;
use Illuminate\Contracts\Validation\Validator;

class StorePurchaseOrderAndDetailsRequest extends FormRequest
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
            'purchase_order_details' => 'required|array',
            'purchase_order_details.*.product_id' => 'required|numeric',
            'purchase_order_details.*.quantity' => 'required|numeric',
            'purchase_order_details.*.price' => 'required|numeric',
            'purchase_order_details.*.expiration_date' => 'nullable|date',
            'company_id' => 'required|numeric',
            'provider_id' => 'required|numeric',
            'warehouse_id' => 'required|numeric',
            'user_id' => 'required|numeric',
            'status' => 'nullable|string',
            'amount' => 'required',
            'expected_delivery' => 'nullable|date',
            'notes' => 'nullable|string',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        ApiResponseHelper::validationError($validator);
    }
}