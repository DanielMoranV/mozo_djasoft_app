<?php

namespace App\Http\Requests;

use App\Classes\ApiResponseHelper;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreCompanyRequest extends FormRequest
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
            'company_name' => 'required|string|max:255',
            'ruc' => [
                'required',
                'string',
                'regex:/^(10|20)\d{9}$/',
            ],
            'address' => 'required|string|max:255',
            'phone' => 'nullable|string|max:10',
            'status' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'logo' => 'nullable|file|image',
            'sol_user' => 'nullable|string|max:255',
            'sol_pass' => 'nullable|string|max:255',
            'cert' => 'nullable|file|mimes:pem,txt',
            'client_id' => 'nullable|string|max:255',
            'client_secret' => 'nullable|string|max:255',
            'production' => 'nullable|boolean',
        ];
    }
    public function failedValidation(Validator $validator)
    {
        ApiResponseHelper::validationError($validator);
    }
}