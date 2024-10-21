<?php

namespace App\Http\Requests;

use App\Classes\ApiResponseHelper;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateUserRequest extends FormRequest
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
            'name' => 'nullable',
            'dni' => 'nullable|min:8|max:8',
            'email' => 'nullable|email',
            'password' => 'nullable|min:8',
            'photo_profile' => 'nullable|file|image',
            'is_valid' => 'nullable|boolean'
        ];
    }


    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        if ($this->has('is_valid')) {
            $this->merge([
                'is_valid' => $this->is_valid ? 1 : 0,
            ]);
        }
    }

    public function failedValidation(Validator $validator)
    {
        ApiResponseHelper::validationError($validator);
    }
}
