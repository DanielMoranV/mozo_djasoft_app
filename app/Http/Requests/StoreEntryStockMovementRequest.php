<?php

namespace App\Http\Requests;

use App\Classes\ApiResponseHelper;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;


class StoreEntryStockMovementRequest extends FormRequest
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
            'purchaseOrder_id' => 'nullable|numeric',
            'voucher' => 'required|array',
            'voucher.series' => 'required|string|max:4',
            'voucher.number' => 'required|string|max:8',
            'voucher.amount' => 'required|decimal:0,99',
            'voucher.status' => 'nullable|string',
            'voucher.issue_date' => 'nullable|date',
            'user_id' => 'required|numeric',
            'comment' => 'nullable|string',
            'category_movements_id' => 'required|numeric|exists:category_movements,id',
            'provider_id' => 'required|numeric',
            'warehouse_id' => 'required|numeric',
            'movements_details' => 'required|array',
            'movements_details.*.product_id' => 'required|numeric',
            'movements_details.*.count' => 'required|numeric',
            'movements_details.*.expiration_date' => 'nullable|date',
            'movements_details.*.price' => 'required|decimal:0,99',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        ApiResponseHelper::validationError($validator);
    }
}