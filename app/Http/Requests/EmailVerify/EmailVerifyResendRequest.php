<?php

namespace App\Http\Requests\EmailVerify;

class EmailVerifyResendRequest extends EmailVerifyBaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return parent::checkIfAlreadyVerified();
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
