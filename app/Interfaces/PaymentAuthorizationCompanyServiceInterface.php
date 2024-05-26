<?php

namespace App\Interfaces;

interface PaymentAuthorizationCompanyServiceInterface
{
    public function authorize(array $params);
}
