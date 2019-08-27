<?php

declare(strict_types=1);

namespace AkeneoDAMConnector\Infrastructure\Pim;

use Akeneo\PimEnterprise\ApiClient\AkeneoPimEnterpriseClientBuilder;
use Akeneo\PimEnterprise\ApiClient\AkeneoPimEnterpriseClientInterface;

class ClientBuilder
{
    /** @var string */
    private $clientId;

    /** @var string */
    private $secret;

    /** @var string */
    private $baseUri;

    /** @var AkeneoPimEnterpriseClientInterface */
    private $client;

    public function __construct(
        string $baseUri,
        string $clientId,
        string $secret,
        string $username,
        string $password
    ) {
        $this->baseUri = $baseUri;
        $this->clientId = $clientId;
        $this->secret = $secret;
        $clientBuilder = new AkeneoPimEnterpriseClientBuilder($this->baseUri);
        $this->client = $clientBuilder->buildAuthenticatedByPassword(
            $this->clientId,
            $this->secret,
            $username,
            $password
        );
    }

    public function getClient(): AkeneoPimEnterpriseClientInterface
    {
        return $this->client;
    }
}
