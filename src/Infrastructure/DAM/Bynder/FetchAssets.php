<?php

declare(strict_types=1);

namespace AkeneoDAMConnector\Infrastructure\DAM\Bynder;

use AkeneoDAMConnector\Application\DamAdapter\FetchAssets as FetchAssetsInterface;
use AkeneoDAMConnector\Domain\Asset\DamAsset;
use AkeneoDAMConnector\Domain\Asset\DamAssetIdentifier;
use AkeneoDAMConnector\Domain\AssetFamilyCode;
use Bynder\Api\Impl\BynderApi;

class FetchAssets implements FetchAssetsInterface
{
    private const FAMILY_CODE_PROPERTY_NAME = 'PIM_asset_family';
    private const LIMIT = 20;

    /** @var BynderApi */
    private $client;

    public function __construct(
        ClientBuilder $clientBuilder
    ) {
        $this->client = $clientBuilder->getClient();
    }

    public function fetch(AssetFamilyCode $assetFamilyCode, ?\DateTimeInterface $lastFetchDate): \Iterator
    {
        if (null === $lastFetchDate) {
            $lastFetchDate = new \DateTimeImmutable('@0');
        }

        foreach ($this->getFetchMediasIterator((string)$assetFamilyCode, $lastFetchDate) as $media) {
            $damAsset = new DamAsset(
                new DamAssetIdentifier($media['id']),
                $assetFamilyCode,
                null
            );
            foreach ($media as $property => $value) {
                if (is_array($value)) {
                    foreach ($value as $subProperty => $subValue) {
                        $damAsset->addValue(sprintf('%s_%s', $property, (string)$subProperty), (string)$subValue);
                    }
                } else {
                    $damAsset->addValue($property, (string)$value);
                }
            }

            yield $damAsset;
        }
    }

    private function getFetchMediasIterator(
        string $familyCode,
        \DateTimeInterface $fromDate
    ): \Iterator {
        $mediaFilter = [
            'property_'.self::FAMILY_CODE_PROPERTY_NAME => $familyCode,
            'dateModified' => $fromDate->format('c'),
            'orderBy' => 'dateModified asc',
            'total' => 1,
            'limit' => self::LIMIT,
            'page' => 0,
        ];

        $total = null;
        do {
            $mediaFilter['page']++;

            $result = $this->client->getAssetBankManager()->getMediaList($mediaFilter)->wait();
            if (null === $total) {
                $total = $result['total']['count'];
            }

            yield from $result['media'];
        } while ($mediaFilter['page'] * self::LIMIT < $total);
    }
}
