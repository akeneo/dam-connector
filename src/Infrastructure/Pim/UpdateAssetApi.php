<?php

declare(strict_types=1);

namespace AkeneoDAMConnector\Infrastructure\Pim;

use Akeneo\PimEnterprise\ApiClient\Api\AssetManager\AssetApiInterface;
use AkeneoDAMConnector\Application\PimAdapter\UpdateAsset;
use AkeneoDAMConnector\Domain\Asset\PimAsset;
use AkeneoDAMConnector\Domain\AssetFamilyCode;

class UpdateAssetApi implements UpdateAsset
{
    /** @var AssetApiInterface */
    private $api;

    private $assets = [];

    public function __construct(ClientBuilder $clientBuilder)
    {
        $this->api = $clientBuilder->getClient()->getAssetManagerApi();
    }

    public function upsert(AssetFamilyCode $assetFamilyCode, PimAsset $asset): void
    {
        $this->assets[(string)$assetFamilyCode][] = $asset;

        if (count($this->assets) > 100) {
            $this->flush($assetFamilyCode);
        }
    }

    public function flush(AssetFamilyCode $assetFamilyCode): void
    {
        $this->api->upsertList(
            (string)$assetFamilyCode,
            array_map(
                function (PimAsset $pimAsset) {
                    return $pimAsset->normalize();
                },
                $this->assets[(string)$assetFamilyCode]
            )
        );

        $this->assets[(string)$assetFamilyCode] = [];
    }
}
