<?php

namespace App\Exceptions\Account;

use Symfony\Component\HttpFoundation\Response;

class AccountTypeNotFoundException extends \Exception
{
    public function __construct(string $type)
    {
        parent::__construct("Account type not found: $type", Response::HTTP_NOT_FOUND);
    }
}
