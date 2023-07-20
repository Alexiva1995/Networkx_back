<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rules\Password;

class UserStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'user_name' => 'required|string|max:255',
            'user_lastname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            // 'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required',
            'password' => [
                'required', 'string', 'confirmed',
                Password::min(8) // Debe tener por lo menos 8 caracteres
                            ->mixedCase() // Debe tener mayúsculas + minúsculas
                            ->letters() // Debe incluir letras
                            ->numbers() // Debe incluir números
                            ->symbols(), // Debe incluir símbolos,
            ],
            'prefix_id' => 'required',
            'buyer_id' => 'nullable|exists:users,id',
            'link_code' => 'string|min:6|max:6'
        ];
    }

    public function messages()
    {
        return [
            'prefix_id.required' => 'The country is required',
            'buyer_id.exists' => 'Your referral id is invalid.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors(),
            'status' => true
        ], 422));
    }
}
