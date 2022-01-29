<?php

namespace App\Http\Requests\Account;

use App\Exceptions\AppValidationException;
use App\Http\Requests\BaseRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\MessageBag;

class AccountBaseRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required', 'min:8']
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errorMessage = new MessageBag(['login' => 'Invalid email or password.']);
        throw new AppValidationException($errorMessage);
    }
}
