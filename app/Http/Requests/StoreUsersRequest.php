<?php

// app/Http/Requests/StoreUsersRequest.php
namespace App\Http\Requests;

use App\Classes\ApiResponseHelper;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class StoreUsersRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'users' => 'required|array',
            'users.*.name' => 'required|string|max:255',
            'users.*.dni' => 'required|string|min:8|max:8',
            'users.*.phone' => 'required|string|max:15',
            'users.*.email' => 'required|email',
            'users.*.password' => 'required|string|min:6',
            'users.*.role' => 'nullable|string',
        ];
    }
    public function failedValidation(Validator $validator)
    {
        ApiResponseHelper::validationError($validator);
    }
}
