<?php

declare(strict_types=1);

namespace AkeneoDAMConnector\Infrastructure\Pim;

use Akeneo\PimEnterprise\ApiClient\AkeneoPimEnterpriseClientBuilder;
use Akeneo\PimEnterprise\ApiClient\AkeneoPimEnterpriseClientInterface;

class ClientBuilder
{
    /** @var AkeneoPimEnterpriseClientInterface */
    private $client;

    public function __construct(
        string $baseUri,
        string $clientId,
        string $secret,
        string $username,
        string $password
    ) {
        $clientBuilder = new AkeneoPimEnterpriseClientBuilder($baseUri);
        $this->client = $clientBuilder->buildAuthenticatedByPassword(
            $clientId,
            $secret,
            $username,
            $password
        );
    }

    public function getClient(): AkeneoPimEnterpriseClientInterface
    {
        return $this->client;
    }
}
