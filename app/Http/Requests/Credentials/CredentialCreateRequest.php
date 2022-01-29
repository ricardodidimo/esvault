<?php

namespace App\Http\Requests\Credentials;

class CredentialCreateRequest extends CredentialBaseRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return parent::rules();
    }
}
