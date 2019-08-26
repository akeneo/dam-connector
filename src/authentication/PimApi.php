<?php

declare(strict_types=1);

namespace authentication;

use Akeneo\PimEnterprise\ApiClient;

class PimApi
{
    private $token;
    private $refreshToken;
    private $client;

    public function __construct()
    {
        $clientBuilder = new ApiClient\AkeneoPimEnterpriseClientBuilder('http://localhost:8080/');

        $passwordClient = $clientBuilder->buildAuthenticatedByPassword(
            '',
            '',
            '',
            ''
        );

        $passwordClient->getChannelApi()->all();

        $this->token = $passwordClient->getToken();
        $this->refreshToken = $passwordClient->getRefreshToken();

        $this->client = $clientBuilder->buildAuthenticatedByToken(
            '',
            '',
            $this->token,
            $this->refreshToken
        );
    }

    public function listFamilyAttribute(string $familyCode): array
    {
        return $this->client->getAssetFamilyAttributeApi()->all($familyCode);
    }

    public function upsertFamilyAttribute(string $code, string $attribute, array $data)
    {
        return $this->client->getAssetFamilyAttributeApi()->upsert($code, $attribute, $data);
    }

    public function upsertFamily(string $code, array $data = [])
    {
        return $this->client->getAssetFamilyApi()->upsert($code, $data);
    }

    private function refreshClientAuthenticatedByToken()
    {
        $clientBuilder = new ApiClient\AkeneoPimEnterpriseClientBuilder('http://localhost:8080/');

        return $clientBuilder->buildAuthenticatedByPassword(
            getenv('AKENEO_CLIENT_ID'),
            getenv('AKENEO_SECRET'),
            getenv('AKENEO_USERNAME'),
            getenv('AKENEO_PASSWORD')
        );
    }
}


//$client = $clientBuilder->buildAuthenticatedByToken(getenv('AKENEO_CLIENT_ID'), getenv('AKENEO_SECRET'), $tokenAdmin, $refreshTokenAdmin);
