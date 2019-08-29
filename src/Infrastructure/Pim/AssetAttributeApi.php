<?php

declare(strict_types=1);

namespace AkeneoDAMConnector\Infrastructure\Pim;

use Akeneo\PimEnterprise\ApiClient\Api\AssetManager\AssetAttributeApiInterface;
use AkeneoDAMConnector\Domain\Pim\AssetAttribute;

class AssetAttributeApi
{
    /** @var AssetAttributeApiInterface */
    private $api;

    public function __construct(ClientBuilder $clientBuilder)
    {
        $this->api = $clientBuilder->getClient()->getAssetAttributeApi();
    }

    public function fetchAll(string $familyCode): array
    {
        return array_map(function (array $attribute) {
            return new AssetAttribute(
                $attribute['code'],
                $attribute['type'],
                $attribute['value_per_locale']
            );
        }, $this->api->all($familyCode));
    }

    public function upsert(string $familyCode, string $attributeCode, array $data): int
    {
        return $this->api->upsert($familyCode, $attributeCode, $data);
    }
}
