<?php

namespace App\Http\Requests\User;

class UserUpdateRequest extends UserBaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['nullable', 'alpha_dash'],
            'email' => ['nullable', 'email', 'unique:users'],
            'password' => ['nullable', 'min:8'],
            'current_password' => ['required', 'current_password:sanctum']
        ];
    }

    public function attributes()
    {
        return [
            'current_password' => 'current password'
        ];
    }
}
