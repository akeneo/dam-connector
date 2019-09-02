<?php

declare(strict_types=1);

namespace AkeneoDAMConnector\Infrastructure\DAM\Fake;

use AkeneoDAMConnector\Application\DamAdapter\FetchAssets as FetchAssetsInterface;
use AkeneoDAMConnector\Domain\Asset\DamAsset;
use AkeneoDAMConnector\Domain\Asset\DamAssetCollection;
use AkeneoDAMConnector\Domain\Asset\DamAssetIdentifier;
use AkeneoDAMConnector\Domain\AssetFamilyCode;
use AkeneoDAMConnector\Domain\Locale;
use AkeneoDAMConnector\Domain\ResourceType;

class FetchAssets implements FetchAssetsInterface
{
    public function fetch(\DateTime $lastFetchDate, AssetFamilyCode $assetFamilyCode): DamAssetCollection
    {
        $assetFamilyCode = new AssetFamilyCode('packshot');

        $collection = new DamAssetCollection();

        for ($i = 0; $i < 2; $i++) {
            $id = (string)$i;

            $asset = new DamAsset(
                new DamAssetIdentifier("id_{$id}"), $assetFamilyCode, new Locale('en_US'), new ResourceType('image')

            );
            $asset->addValue('sku', "sku_{$id}");
            $asset->addValue('unused_property', 'unused_value');
            $asset->addValue('url', "/preview/{$id}.png");
            $asset->addValue('colors', 'blue, red, green');
            $asset->addValue('country', 'France');

            $collection->addAsset($asset);
        }

        return $collection;
    }
}
