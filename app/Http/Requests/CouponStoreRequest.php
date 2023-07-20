<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;

class CouponStoreRequest extends FormRequest
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
        $new_date = Carbon::now()->addDays(31)->toDateString();       
        return [
            'percentage' => 'required',
            'percentage' => [Rule::in([5,10,20])],
            // 'stock' => 'required',
            'expiration' => 'required|after:today|before:'.$new_date
        ];
    }

    public function messages()
    {
        return [
            'percentage.required' => 'The discount percentage is required',
            // 'stock.required' => 'Stock quantity is required',
            'expiration.required' => 'The expiration date is required',
            'expiration.after' => 'The expiration date must be after today',
            'expiration.before' => 'The expiration date must be less or equal than 30 days',
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
