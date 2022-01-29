<?php

namespace App\Http\Requests\User;

class UserCreateRequest extends UserBaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['required', 'alpha_dash'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'confirmed', 'min:8']
        ];
    }
}
