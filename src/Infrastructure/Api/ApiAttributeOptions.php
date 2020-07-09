<?php

declare(strict_types=1);

namespace PimApiTest\Infrastructure\Api;

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

    public function getAttributeOptionsFromProduct(array $product, array $attributes): array
    {
        $attributeOptions = [];
        foreach ($product['values'] as $attributeCode => $values) {
            if (
                !in_array($attributes[$attributeCode]['type'], ['pim_catalog_simpleselect', 'pim_catalog_multiselect']) &&
                !array_key_exists($attributeCode, $attributeOptions)
            ) {
                continue;
            }
            $attributeOptions[$attributeCode] = $this->apiAttributeOptions->all((string) $attributeCode);
        }

        return $attributeOptions;
    }
}
