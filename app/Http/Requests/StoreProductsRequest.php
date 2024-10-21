<?php

namespace App\Http\Requests;

use App\Classes\ApiResponseHelper;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductsRequest extends FormRequest
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
            'products' => 'required|array',
            'products.*.code' => 'nullable|string|max:255',
            'products.*.name' => 'nullable|string|max:255',
            'products.*.description' => 'nullable|string|max:255',
            'products.*.user_id' => 'nullable|numeric|exists:users,id',
            'products.*.category_id' => 'nullable|numeric|exists:categories,id',
            'products.*.unit_id' => 'nullable|numeric|exists:units,id'
        ];
    }


    public function failedValidation(Validator $validator)
    {
        ApiResponseHelper::validationError($validator);
    }
}
