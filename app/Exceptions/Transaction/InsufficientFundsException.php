<?php

namespace App\Exceptions\Transaction;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class InsufficientFundsException extends Exception
{
    public function __construct()
    {
        $message = 'Payer does not have enough funds to complete the transaction';

        parent::__construct($message, Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
