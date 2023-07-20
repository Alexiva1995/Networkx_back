<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class KycStoreRequest extends FormRequest
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
            'file_front' => 'required|mimes:jpg,jpeg,png,svg|max:2000',
		    'file_back' => 'required|mimes:jpg,jpeg,png,svg|max:2000',
        ];
    }

    public function messages()
    {
        return [
             //* File Front
             'file_front.max' => 'The file front must not be greater than 2 MB.',
             'file_front.required' => 'Front part of your document is required.',
             'file_front.mimes' => 'The file front must be a file type of png, jpeg, jpg or svg.',
             //* File Back
             'file_back.max' => 'The file back must not be greater than 2 MB.',
             'file_back.required' => 'Back part of your document is required.',
             'file_back.mimes' => 'The file back must be a file type of png, jpeg, jpg or svgd.',
        ];
    }


    protected function failedValidation(Validator $validator)
    {   $errors = $validator->errors()->all();
        throw new HttpResponseException(response()->json([
            'msg' => $errors[0],
            'status' => 'error'
        ], 422));
    }
}
