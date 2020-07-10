<?php

declare(strict_types=1);

namespace PimApiTest\Infrastructure\Api;

use Akeneo\PimEnterprise\ApiClient\AkeneoPimEnterpriseClientInterface;
use PimApiTest\Infrastructure\Pim\ClientBuilder;

class ApiAttribute
{
    /** @var AkeneoPimEnterpriseClientInterface */
    private $client;

    public function __construct(ClientBuilder $clientBuilder)
    {
        $this->client = $clientBuilder->getClient();
    }

    public function get(): array
    {
        $attributes = [];
        foreach ($this->client->getAttributeApi()->all(100) as $attribute) {
            $attributes[$attribute['code']] = $attribute;
        }

        return $attributes;
    }
}
