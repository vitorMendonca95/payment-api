<?php

namespace App\Exceptions\Transaction;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class PayerOrPayeeNotFoundException extends Exception
{
    public function __construct()
    {
        $message = 'Payer or Payee not found';

        parent::__construct($message, Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
