<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;


class FormularyStoreRequest extends FormRequest
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
            'project_id' => 'required',
            'name' => 'required',
            'login' => 'required',
            'password' => 'required',
            'leverage' => 'required',
            'balance' => 'required',
            'serverr' => 'required',
            'date' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'the field name is required',
            'login.required' => 'the field login is required',
            'password.required' => 'the field password is required',
            'leverage.required' => 'the field leverage is required',
            'balance.required' => 'the field balance is required',
            'serverr.required' => 'the field server is required',
            'date.required' => 'the field date is required',
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
