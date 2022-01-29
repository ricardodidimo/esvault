<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\MessageBag;

class AppValidationException extends Exception
{
    private MessageBag $errors;

    public function __construct(MessageBag $errors)
    {
        $this->errors = $errors;
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
