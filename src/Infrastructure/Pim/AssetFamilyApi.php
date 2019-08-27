<?php

declare(strict_types=1);

namespace AkeneoDAMConnector\Infrastructure\Pim;

use Akeneo\PimEnterprise\ApiClient\Api\AssetManager\AssetFamilyApiInterface;

class AssetFamilyApi
{
    /** @var AssetFamilyApiInterface */
    private $api;

    public function __construct(ClientBuilder $clientBuilder)
    {
        $this->api = $clientBuilder->getClient()->getAssetFamilyApi();
    }

    public function upsertFamily(string $familyCode, array $data): int
    {
        return $this->api->upsert($familyCode, $data);
    }
}
