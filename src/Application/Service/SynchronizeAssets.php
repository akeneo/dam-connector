<?php

declare(strict_types=1);

namespace AkeneoDAMConnector\Application\Service;

use AkeneoDAMConnector\Application\DamAdapter\FetchAssets;
use AkeneoDAMConnector\Application\Mapping\AssetTransformer;
use AkeneoDAMConnector\Domain\AssetFamilyCode;
use AkeneoDAMConnector\Infrastructure\Pim\UpdateAssetApi;

class SynchronizeAssets
{
    /** @var FetchAssets */
    private $fetchAssets;

    /** @var AssetTransformer */
    private $assetTransformer;

    /** @var UpdateAssetApi */
    private $assetApi;

    public function __construct(
        FetchAssets $fetchAssets,
        AssetTransformer $assetTransformer,
        UpdateAssetApi $assetApi
    ) {
        $this->fetchAssets = $fetchAssets;
        $this->assetTransformer = $assetTransformer;
        $this->assetApi = $assetApi;
    }

    public function execute(AssetFamilyCode $assetFamilyCode, ?\DateTimeInterface $lastFetchDate): void
    {
        $damAssets = $this->fetchAssets->fetch($assetFamilyCode, $lastFetchDate);

        foreach ($damAssets as $damAsset) {
            $pimAsset = $this->assetTransformer->damToPim($damAsset);

            $this->assetApi->upsert($assetFamilyCode, $pimAsset);
        }

        $this->assetApi->flush($assetFamilyCode);
    }
}
