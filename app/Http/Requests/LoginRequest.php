<?php

namespace App\Http\Requests;

class LoginRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'email' => [
                'bail',
                'required',
                'string'
            ],
            'password' => [
                'bail',
                'required',
                'string'
            ]
        ];
    }
}
