<?php

declare(strict_types=1);

namespace AkeneoDAMConnector\Infrastructure\Pim;

use Akeneo\PimEnterprise\ApiClient\Api\AssetManager\AssetApiInterface;
use AkeneoDAMConnector\Application\PimAdapter\UpdateAsset;
use AkeneoDAMConnector\Domain\Asset\PimAsset;
use AkeneoDAMConnector\Domain\AssetFamilyCode;

class UpdateAssetApi implements UpdateAsset
{
    private const BATCH_SIZE = 100;

    /** @var AssetApiInterface */
    private $api;

    private $assets = [];

    /** @var AttributeOptionsApi */
    private $attributeOptionsApi;

    public function __construct(ClientBuilder $clientBuilder, AttributeOptionsApi $attributeOptionsApi)
    {
        $this->api = $clientBuilder->getClient()->getAssetManagerApi();
        $this->attributeOptionsApi = $attributeOptionsApi;
    }

    public function upsert(AssetFamilyCode $assetFamilyCode, PimAsset $asset): void
    {
        $this->assets[(string) $assetFamilyCode][] = $asset->normalize();
        $this->attributeOptionsApi->upsertAttributeOptions($assetFamilyCode, $asset->getAttributeOptions());
        if (count($this->assets) >= self::BATCH_SIZE) {
            $this->flush($assetFamilyCode);
        }
    }

    public function flush(AssetFamilyCode $assetFamilyCode): void
    {
        $this->attributeOptionsApi->flush($assetFamilyCode);

        $results = $this->api->upsertList((string)$assetFamilyCode, $this->assets[(string)$assetFamilyCode]);
        var_dump($results); // TODO Handle errors

        $this->assets[(string)$assetFamilyCode] = [];
    }
}
