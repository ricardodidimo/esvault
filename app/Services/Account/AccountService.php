<?php

namespace App\Services\Account;

use App\Exceptions\AppValidationException;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\MessageBag;

class AccountService implements IAccountService
{
    public function currentUser(): Authenticatable
    {
        return Auth::user();
    }

    public function login(array $credentials): void
    {
        if (Auth::attempt($credentials)) {
            session()->regenerate();
            return;
        }

        $error = new MessageBag(['login' => 'Invalid email or password.']);
        throw new AppValidationException($error);
    }
}
