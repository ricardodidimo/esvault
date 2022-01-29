<?php

namespace App\Http\Requests\EmailVerify;

use App\Exceptions\AppValidationException;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\MessageBag;

class EmailVerifyBaseRequest extends EmailVerificationRequest
{
    /**
     * @throws AppValidationException If requesting user already has a verified email.
     */
    protected function checkIfAlreadyVerified(): bool
    {
        if ($this->user()->hasVerifiedEmail()) {
            $errors = new MessageBag(
                ['verification' => 'Email already verified.']
            );
            throw new AppValidationException($errors);
        }

        return true;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return parent::authorize() && $this->checkIfAlreadyVerified();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
