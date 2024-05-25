<?php

namespace App\Providers;

use App\Interfaces\AuthorizationCompanyServiceInterface;
use App\Services\ExternalProvider\AuthorizationCompany\AuthorizationCompanyService;
use Carbon\Laravel\ServiceProvider;
use GuzzleHttp\Client;
use Tests\Stub\AuthorizationCompanyServiceStub;

class AuthorizationCompanyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(AuthorizationCompanyServiceInterface::class, AuthorizationCompanyService::class);
    }
}
