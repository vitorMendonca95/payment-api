<?php

namespace App\Services\ExternalProvider\PaymentAuthorization;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class CompanyXService
{
    private string $baseUrl;

    public function __construct(private readonly Client $client)
    {
        $this->baseUrl = config('services.payment_authorization_companies.company_x.base_url');
    }

    /**
     * @throws GuzzleException
     */
    public function authorize(array $params)
    {
        $path = config('services.payment_authorization_companies.company_x.authorize_path');

        $response = $this->client->get($this->baseUrl . $path);

        return json_decode($response->getBody()->getContents(), true);
    }
}
