<?php

declare(strict_types=1);

namespace AkeneoDAMConnector\Infrastructure\Pim;

use Akeneo\PimEnterprise\ApiClient\Api\AssetManager\AssetApiInterface;
use AkeneoDAMConnector\Domain\AssetFamily;
use AkeneoDAMConnector\Domain\Pim\AssetCollection;

class AssetApi
{
    /** @var AssetApiInterface */
    private $api;

    public function __construct(ClientBuilder $clientBuilder)
    {
        $this->api = $clientBuilder->getClient()->getAssetManagerApi();
    }

    public function upsertList(AssetFamily $family, AssetCollection $assets): array
    {
        return $this->api->upsertList($family->getCode(), $assets->normalize());
    }
}
