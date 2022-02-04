<?php

namespace App\Http\Requests\Account;

use App\Exceptions\AppValidationException;
use App\Http\Requests\BaseRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\MessageBag;

class AccountConfirmRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        return [
            'current_password' => ['required', 'current_password:sanctum']
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errorMessage = new MessageBag(['current_password' => 'The password is incorrect.']);
        throw new AppValidationException($errorMessage);
    }
}
