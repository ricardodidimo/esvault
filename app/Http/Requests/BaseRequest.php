<?php

namespace App\Http\Requests;

use App\Exceptions\AppAuthorizationException;
use App\Exceptions\AppValidationException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class BaseRequest extends FormRequest
{
    public function failedAuthorization()
    {
        throw new AppAuthorizationException('Unauthorized to perform such action.');
    }

    protected function failedValidation(Validator $validator)
    {
        throw new AppValidationException($validator->errors());
    }
}
