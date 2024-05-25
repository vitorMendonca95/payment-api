<?php

namespace App\Services\ExternalProvider\AuthorizationCompany;

use App\Interfaces\AuthorizationCompanyServiceInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class AuthorizationCompanyService implements AuthorizationCompanyServiceInterface
{
    private string $baseUrl;

    public function __construct(private readonly Client $client)
    {
        $this->baseUrl = config('services.authorization_company.base_url');
    }

    /**
     * @throws GuzzleException
     */
    public function authorize(array $params = [])
    {
        $path = config('services.authorization_company.authorize_path');

        $response = $this->client->get($this->baseUrl . $path);

        return json_decode($response->getBody()->getContents(), true);
    }
}
