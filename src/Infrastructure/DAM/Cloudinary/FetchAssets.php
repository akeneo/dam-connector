<?php

declare(strict_types=1);

namespace AkeneoDAMConnector\Infrastructure\DAM\Cloudinary;

use AkeneoDAMConnector\Application\DamAdapter\FetchAssets as FetchAssetsInterface;
use AkeneoDAMConnector\Domain\Model\Dam\DamAsset;
use AkeneoDAMConnector\Domain\Model\Dam\DamAssetIdentifier;
use AkeneoDAMConnector\Domain\Model\FamilyCode;
use AkeneoDAMConnector\Domain\Model\Locale;

/**
 * This class is not scalable for an high volume of assets
 *
 * Cloudinary does not provide any delta to fetch assets from a last update date
 * They use a queuing system for that
 */
class FetchAssets implements FetchAssetsInterface
{
    /** @var Search */
    private $search;

    public function __construct(Search $search)
    {
        $this->search = $search;
    }

    public function fetch(FamilyCode $assetFamilyCode, ?\DateTimeInterface $lastFetchDate): \Iterator
    {
        $expression = sprintf('tags:akeneo AND folder="%s"', (string) $assetFamilyCode);
        $response = $this->search->search($expression, ['tags', 'context']);

        $staticAttributes = ['filename', 'url', 'secure_url', 'status', 'public_id'];

        $damAssets = [];

        $assets = $response['resources'] ?? [];
        foreach ($assets as $asset) {
            $damAsset = new DamAsset(
                new DamAssetIdentifier($asset['filename']),
                $assetFamilyCode,
                new Locale('en_US')
            );

            foreach ($staticAttributes as $staticAttribute) {
                $damAsset->addValue($staticAttribute, $asset[$staticAttribute]);
            }

            foreach ($asset['context'] as $property => $value) {
                $damAsset->addValue($property, $value);
            }
            $damAsset->addValue('tags', implode(', ', $asset['tags']));

            $damAssets[] = $damAsset;
        }

        return new \ArrayIterator($damAssets);
    }
}
