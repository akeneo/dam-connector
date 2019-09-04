<?php

declare(strict_types=1);

namespace AkeneoDAMConnector\Infrastructure\Pim;

use Akeneo\PimEnterprise\ApiClient\Api\AssetManager\AssetApiInterface;
use AkeneoDAMConnector\Application\PimAdapter\UpdateAsset;
use AkeneoDAMConnector\Domain\AssetFamilyCode;
use AkeneoDAMConnector\Domain\Asset\PimAssetCollection;

class UpdateAssetApi implements UpdateAsset
{
    /** @var AssetApiInterface */
    private $api;

    public function __construct(ClientBuilder $clientBuilder)
    {
        $this->api = $clientBuilder->getClient()->getAssetManagerApi();
    }

    public function upsertList(AssetFamilyCode $assetFamilyCode, PimAssetCollection $assets): array
    {
        return $this->api->upsertList((string) $assetFamilyCode, $assets->normalize());
    }
}
