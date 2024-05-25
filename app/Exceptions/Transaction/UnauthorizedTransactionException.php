<?php

namespace App\Exceptions\Transaction;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class UnauthorizedTransactionException extends Exception
{
    public function __construct()
    {
        $message = 'Transaction authorization failed with the payment service.';

        parent::__construct($message, Response::HTTP_UNAUTHORIZED);
    }
}
