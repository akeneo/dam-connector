<?php

declare(strict_types=1);

namespace AkeneoDAMConnector\Infrastructure\Pim;

use Akeneo\PimEnterprise\ApiClient\Api\AssetManager\AssetApiInterface;
use AkeneoDAMConnector\Application\PimAdapter\UpdateAsset;
use AkeneoDAMConnector\Domain\AssetFamily;
use AkeneoDAMConnector\Domain\Pim\AssetCollection;

class UpdateAssetApi implements UpdateAsset
{
    /** @var AssetApiInterface */
    private $api;

    public function __construct(ClientBuilder $clientBuilder)
    {
        $this->api = $clientBuilder->getClient()->getAssetManagerApi();
    }

    public function upsertList(AssetFamily $family, AssetCollection $assets): void
    {
        $this->api->upsertList($family->getCode(), $assets->normalize());
    }
}
