<?php

declare(strict_types=1);

namespace AkeneoDAMConnector\Infrastructure\Pim;

use Akeneo\PimEnterprise\ApiClient\Api\AssetManager\AssetFamilyApiInterface;
use AkeneoDAMConnector\Domain\AssetFamily;

class AssetFamilyApi
{
    /** @var AssetFamilyApiInterface */
    private $api;

    public function __construct(ClientBuilder $clientBuilder)
    {
        $this->api = $clientBuilder->getClient()->getAssetFamilyApi();
    }

    public function fetchAll(): array
    {
        return array_map(function (array $family) {
            return new AssetFamily($family['code']);
        }, iterator_to_array($this->api->all()));
    }

    public function upsertFamily(string $familyCode, array $data): int
    {
        return $this->api->upsert($familyCode, $data);
    }
}
