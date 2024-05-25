<?php

namespace App\Exceptions\Transaction;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class AccountTransferNotAllowedException extends Exception
{
    public function __construct(string $accountType)
    {
        $message = $accountType . ' accounts are not allowed to transfer money';

        parent::__construct($message, Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
