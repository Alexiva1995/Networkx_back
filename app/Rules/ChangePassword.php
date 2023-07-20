<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Http;

class ChangePassword implements Rule
{
    public $id;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {   
        $data = ['id' => $this->id]; 

        $url = config('services.backend_auth.base_uri');

        $response = Http::withHeaders([
            'apikey' => config('services.backend_auth.key'),
        ])->post("{$url}check-password", $data);
        // dd($response);
        $responseObject = $response->object();

        return Hash::check($value, $responseObject->password);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Old password does not match.';
    }
}
