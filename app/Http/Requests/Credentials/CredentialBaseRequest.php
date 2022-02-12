<?php

namespace App\Http\Requests\Credentials;

use App\Exceptions\AppAuthorizationException;
use App\Exceptions\AppForbiddenException;
use App\Exceptions\AppValidationException;
use App\Http\Requests\BaseRequest;
use App\Models\Credential;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\MessageBag;

class CredentialBaseRequest extends BaseRequest
{
    protected ?Credential $credentialInstance;

    public function getRetrievedCredentialInstance()
    {
        return $this->credentialInstance;
    }

    private function isShowRoute(): bool
    {
        return $this->route('id') != null && $this->method() === 'GET';
    }

    private function queryForCredentialInstance($givenID): void
    {
        $columnsForQuery = $this->isShowRoute() ?
            ['id', 'title', 'description', 'first_claim', 'second_claim', 'user_id'] :
            ['id', 'user_id'];

        $this->credentialInstance = Credential::where('id', $givenID)->first($columnsForQuery);
    }

    public function entityExists(): bool
    {
        $givenID = $this->route('id');
        $this->queryForCredentialInstance($givenID);

        return $this->credentialInstance != null;
    }

    public function haveOwnership(): bool
    {
        return $this->credentialInstance->user_id === Auth::user()->id;
    }

    public function authorize()
    {
        if ($this->entityExists() === false) {
            $error = new MessageBag(['id' => 'Invalid ID given.']);
            throw new AppValidationException($error);
        }

        if ($this->haveOwnership() === false) {
            throw new AppForbiddenException('You do not own this data. Invalid action taken.');
        };

        return true;
    }

    public function rules()
    {
        if ($this->method() === 'POST' || $this->method() === 'PUT') {
            return [
                'title' => ['required', 'max:40'],
                'description' => ['nullable', 'max:140'],
                'first_claim' => ['required'],
                'second_claim' => ['required'],
            ];
        }

        return [];
    }

    public function messages()
    {
        return [
            'max' => ':attribute must not exceed limit of :max characters.'
        ];
    }

    public function attributes()
    {
        return [
            'first_claim' => 'first claim',
            'second_claim' => 'second claim',
        ];
    }
}
