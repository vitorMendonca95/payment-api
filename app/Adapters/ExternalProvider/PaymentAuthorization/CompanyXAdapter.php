<?php

namespace App\Adapters\ExternalProvider\PaymentAuthorization;

use App\Interfaces\PaymentAuthorizationCompanyServiceInterface;
use App\Services\ExternalProvider\PaymentAuthorization\CompanyXService;
use GuzzleHttp\Exception\GuzzleException;

class CompanyXAdapter implements PaymentAuthorizationCompanyServiceInterface
{
    private CompanyXService $companyXService;

    public function __construct(CompanyXService $authorizationService)
    {
        $this->companyXService = $authorizationService;
    }

    /**
     * @throws GuzzleException
     */
    public function authorize(array $params)
    {
        return $this->companyXService->authorize($params);
    }
}
