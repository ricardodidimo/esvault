<?php

namespace App\Services\Account;

use Illuminate\Contracts\Auth\Authenticatable;

interface IAccountService
{
    public function currentUser(): Authenticatable;

    /**
     * @param array $credentials Login credentials to establish session
     * @throws AppValidationException If credentials doesnt refer to any user in database
     */
    public function login(array $credentials): void;
}
