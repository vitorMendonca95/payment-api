<?php

namespace App\Factories\ExternalProvider\PaymentAuthorization;

use App\Adapters\ExternalProvider\PaymentAuthorization\CompanyXAdapter;
use App\Enums\AvailablePaymentAuthorizationCompanyEnum;
use App\Interfaces\PaymentAuthorizationCompanyServiceInterface;
use Exception;

class PaymentAuthorizationServiceFactory
{
    /**
     * @throws Exception
     */
    public function getAuthorizationService(?string $serviceType = null): PaymentAuthorizationCompanyServiceInterface
    {
        $currentAuthorizationService = $serviceType ?? config(
            'services.current_payment_authorization_company',
            'companyX'
        );

        return match ($currentAuthorizationService) {
            AvailablePaymentAuthorizationCompanyEnum::CompanyX->value => app(CompanyXAdapter::class),
            default => throw new Exception("Unknown service type: $currentAuthorizationService"),
        };
    }
}
