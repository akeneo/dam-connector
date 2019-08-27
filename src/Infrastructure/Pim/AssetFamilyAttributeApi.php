<?php

declare(strict_types=1);

namespace AkeneoDAMConnector\Infrastructure\Pim;

use Akeneo\PimEnterprise\ApiClient\Api\AssetManager\AssetFamilyAttributeApiInterface;

class AssetFamilyAttributeApi
{
    /** @var AssetFamilyAttributeApiInterface */
    private $api;

    public function __construct(ClientBuilder $clientBuilder)
    {
        $this->api = $clientBuilder->getClient()->getAssetFamilyAttributeApi();
    }

    public function upsertFamilyAttribute(string $familyCode, string $attributeCode, array $data): int
    {
        return $this->api->upsert($familyCode, $attributeCode, $data);
    }
}
