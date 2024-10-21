<?php

namespace App\Http\Requests;

use App\Classes\ApiResponseHelper;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Log;

class AuthUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $credentials = $this->only(['dni']);
        $user = User::where('dni', $credentials['dni'])->first();

        return $user && $user->is_active;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'dni' => 'required|string|max:8',
            'password' => 'required|string',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        ApiResponseHelper::validationError($validator);
    }
}
