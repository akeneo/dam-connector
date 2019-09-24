<?php

declare(strict_types=1);

namespace AkeneoDAMConnector\Infrastructure\DAM\DamExample;

use AkeneoDAMConnector\Application\DamAdapter\FetchAssets as FetchAssetsInterface;
use AkeneoDAMConnector\Domain\Asset\DamAsset;
use AkeneoDAMConnector\Domain\Asset\DamAssetIdentifier;
use AkeneoDAMConnector\Domain\AssetFamilyCode;

class FetchAssets implements FetchAssetsInterface
{
    public function fetch(AssetFamilyCode $assetFamilyCode, ?\DateTimeInterface $lastFetchDate): \Iterator
    {
        $response = json_decode($this->getJson(), true);

        foreach ($response[(string) $assetFamilyCode] as $data) {
            $asset = $this->denormalize($data, $assetFamilyCode);

            yield $asset;
        }
    }

    private function denormalize(array $data, AssetFamilyCode $assetFamilyCode): DamAsset
    {
        $asset = new DamAsset(
            new DamAssetIdentifier($data['uid']),
            $assetFamilyCode,
            null
        );

        foreach ($data as $property => $value) {
            if ($property === 'uid') {
                continue;
            }

            $asset->addValue($property, (string) $value);
        }

        return $asset;
    }

    private function getJson(): string
    {
        return <<<JSON
        {
            "packshot": [
                {
                    "uid": "7373ac00a66f13833e2583f455b58fd5",
                    "sku": "SKU-0",
                    "url": "https://cdn.dam.example/0.png",
                    "colors": "red,blue",
                    "updated": "2019-09-24T08:00:00+00:00"
                },
                {
                    "uid": "2b36d2ed4f65d347cd2fced7cfd8b11a",
                    "sku": "SKU-1",
                    "url": "https://cdn.dam.example/1.png",
                    "colors": "red",
                    "updated": "2019-09-24T00:00:00+00:00"
                }
            ],
            "user_instruction": [
                {
                    "uid": "e8fe93a2787198bd5fd5ff99715c41f0",
                    "sku": "SKU-2",
                    "url": "https://cdn.dam.example/2.png",
                    "pages": 12,
                    "updated": "2019-09-24T00:00:00+00:00"
                },
                {
                    "uid": "bb1464f149b65276cf2ac5a8ce1c1106",
                    "sku": "SKU-3",
                    "url": "https://cdn.dam.example/3.png",
                    "pages": 4,
                    "updated": "2019-09-24T00:00:00+00:00"
                }
            ]
        }
JSON;
    }
}
