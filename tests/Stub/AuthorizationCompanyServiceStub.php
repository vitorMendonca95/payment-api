<?php

namespace Tests\Stub;

use App\Interfaces\AuthorizationCompanyServiceInterface;

class AuthorizationCompanyServiceStub implements AuthorizationCompanyServiceInterface
{
    public function authorize(array $params = [])
    {
        return json_decode('{"status" : "success", "data" : { "authorization" : true }}', true);
    }
}
