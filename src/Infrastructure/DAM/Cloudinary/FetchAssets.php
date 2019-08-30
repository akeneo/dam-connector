<?php

declare(strict_types=1);

namespace AkeneoDAMConnector\Infrastructure\DAM\Cloudinary;

use AkeneoDAMConnector\Application\DamAdapter\FetchAssets as FetchAssetsInterface;
use AkeneoDAMConnector\Domain\Asset\DamAsset;
use AkeneoDAMConnector\Domain\Asset\DamAssetCollection;
use AkeneoDAMConnector\Domain\Asset\DamAssetIdentifier;
use AkeneoDAMConnector\Domain\AssetFamily;
use AkeneoDAMConnector\Domain\Locale;
use AkeneoDAMConnector\Domain\ResourceType;

class FetchAssets implements FetchAssetsInterface
{
    /** @var Search */
    private $search;

    public function __construct(Search $search)
    {
        $this->search = $search;
    }

    public function fetch(\DateTime $lastFetchDate, AssetFamily $assetFamily): DamAssetCollection
    {
        $expression = sprintf('tags:akeneo AND folder="%s"', $assetFamily->getCode());
        $response = $this->search->search($expression, ['tags', 'context']);

        $staticAttributes = ['filename', 'url', 'secure_url', 'status', 'public_id'];
        $damAssets = new DamAssetCollection();
        $assets = $response['resources'] ?? [];
        foreach ($assets as $asset) {
            $damAsset = new DamAsset(
                new DamAssetIdentifier($asset['filename']),
                $assetFamily,
                new Locale('en_US'),
                new ResourceType($asset['resource_type'])
            );

            foreach ($staticAttributes as $staticAttribute) {
                $damAsset->addValue($staticAttribute, $asset[$staticAttribute]);
            }

            foreach ($asset['context'] as $property => $value) {
                $damAsset->addValue($property, $value);
            }
            $damAsset->addValue('tags', implode(', ', $asset['tags']));

            $damAssets->addAsset($damAsset);
        }

        return $damAssets;
    }
}
