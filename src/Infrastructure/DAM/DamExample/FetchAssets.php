<?php

declare(strict_types=1);

namespace AkeneoDAMConnector\Infrastructure\DAM\DamExample;

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
                new DamAssetIdentifier(md5("{$assetFamilyCode}_{$id}")),
                $assetFamilyCode,
                new Locale('en_US')
            );
            $asset->addValue('sku', "SKU-{$id}");
            $asset->addValue('updated', (new \DateTime())->format('c'));

            if ((string)$assetFamilyCode === 'packshot') {
                $asset->addValue('url', "https://cdn.dam.example/{$id}.png");
                $asset->addValue('colors', 'red,blue');
            } elseif ((string)$assetFamilyCode === 'user_instruction') {
                $asset->addValue('url', "https://cdn.dam.example/{$id}.pdf");
                $asset->addValue('pages', '12');
            }

            yield $asset;
        }
    }
}
