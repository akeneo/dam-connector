<?php

declare(strict_types=1);

namespace AkeneoDAMConnector\Infrastructure\DAM\Fake;

use AkeneoDAMConnector\Application\DamAdapter\FetchAssets as FetchAssetsInterface;
use AkeneoDAMConnector\Domain\Asset\DamAsset;
use AkeneoDAMConnector\Domain\Asset\DamAssetIdentifier;
use AkeneoDAMConnector\Domain\AssetFamilyCode;
use AkeneoDAMConnector\Domain\Locale;

class FetchAssets implements FetchAssetsInterface
{
    public function fetch(AssetFamilyCode $assetFamilyCode, ?\DateTimeInterface $lastFetchDate): \Iterator
    {
        for ($i = 0; $i < 2; $i++) {
            $id = (string)$i;

            $asset = new DamAsset(
                new DamAssetIdentifier("id_{$id}"),
                new AssetFamilyCode('packshot'),
                new Locale('en_US')
            );
            $asset->addValue('sku', "sku_{$id}");
            $asset->addValue('unused_property', 'unused_value');
            $asset->addValue('url', "/preview/{$id}.png");
            $asset->addValue('photograph', 'claudie');
            $asset->addValue('country', 'France');

            yield $asset;
        }
    }
}
