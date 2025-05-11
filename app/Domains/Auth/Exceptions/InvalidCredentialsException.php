<?php

namespace App\Domains\Auth\Exceptions;

use Exception;

class InvalidCredentialsException extends Exception
{
    public function __construct()
    {
        parent::__construct('The provided credentials are invalid.', 401);
    }
}