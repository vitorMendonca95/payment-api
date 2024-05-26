<?php

namespace Tests\Stub;

use App\Interfaces\PaymentAuthorizationCompanyServiceInterface;

class PaymentAuthorizationCompanyServiceStub implements PaymentAuthorizationCompanyServiceInterface
{
    public function authorize(array $params = [])
    {
        return json_decode('{"status" : "success", "data" : { "authorization" : true }}', true);
    }
}
