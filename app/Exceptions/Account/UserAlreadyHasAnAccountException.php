<?php

namespace App\Exceptions\Account;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class UserAlreadyHasAnAccountException extends Exception
{
    public function __construct()
    {
        parent::__construct('User already has an account.', Response::HTTP_CONFLICT);
    }
}
