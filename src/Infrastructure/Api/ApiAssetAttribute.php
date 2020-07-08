<?php

namespace PimApiTest\Infrastructure\Api;

use Akeneo\PimEnterprise\ApiClient\AkeneoPimEnterpriseClientInterface;
use PimApiTest\Infrastructure\Pim\ClientBuilder;

class ApiAssetAttribute
{
    /** @var AkeneoPimEnterpriseClientInterface */
    private $client;

    public function __construct(ClientBuilder $clientBuilder)
    {
        $this->client = $clientBuilder->getClient();
    }

    public function get(string $assetFamily, string $assetCode): array
    {
        $assetAttributes = [];

        foreach ($this->client->getAssetManagerApi()->get($assetFamily, $assetCode) as $attribute) {
            $assetAttributes[] = $attribute;
        }
        return $assetAttributes;
    }
}