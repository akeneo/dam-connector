<?php
declare(strict_types=1);

namespace PimApiTest\Infrastructure\Api;

use Akeneo\Pim\ApiClient\Pagination\ResourceCursorInterface;
use Akeneo\PimEnterprise\ApiClient\AkeneoPimEnterpriseClientInterface;
use PimApiTest\Infrastructure\Pim\ClientBuilder;

class ApiAttributeOptions
{
    /** @var AkeneoPimEnterpriseClientInterface */
    private $client;

    public function __construct(ClientBuilder $clientBuilder)
    {
        $this->client = $clientBuilder->getClient();
    }

    public function all(string $attributeCode): array
    {
        $options = [];
        foreach ($this->client->getAttributeOptionApi()->all($attributeCode) as $option) {
            $options[$option['code']] = $option['labels'];
        }

        return $options;
    }
}
