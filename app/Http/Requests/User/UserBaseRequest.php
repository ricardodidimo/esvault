<?php

namespace App\Http\Requests\User;

use App\Exceptions\AppForbiddenException;
use App\Exceptions\AppValidationException;
use App\Http\Requests\BaseRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\MessageBag;

class UserBaseRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        return [];
    }

    public function messages(): array
    {
        return [
            'email.email' => 'This email format is not accepted.',
            'email.unique' => 'This email is invalid. Try other.',
            'password.confirmed' => 'Passwords doesn\'t match.',
            'min' => ':attribute must be at least 8 characters long.'
        ];
    }
}
