<?php

declare(strict_types=1);

namespace AkeneoDAMConnector\Infrastructure\DAM\Bynder;

use AkeneoDAMConnector\Application\DamAdapter\FetchAssets as FetchAssetsInterface;
use AkeneoDAMConnector\Domain\Asset\DamAsset;
use AkeneoDAMConnector\Domain\Asset\DamAssetCollection;
use AkeneoDAMConnector\Domain\AssetFamily;
use AkeneoDAMConnector\Domain\Locale;
use AkeneoDAMConnector\Domain\ResourceType;
use Bynder\Api\Impl\BynderApi;

/**
 * @author Willy Mesnage <willy.mesnage@akeneo.com>
 */
class FetchAssets implements FetchAssetsInterface
{
    /** @var BynderApi */
    private $client;

    public function __construct(ClientBuilder $clientBuilder)
    {
        $this->client = $clientBuilder->getClient();
    }

    /**
     * {@inheritdoc}
     */
    public function fetch(\DateTime $lastFetchDate, AssetFamily $assetFamily): DamAssetCollection
    {
        $mediaList = $this->fetchMediaList($lastFetchDate, $assetFamily);
        $collection = new DamAssetCollection();

        foreach ($mediaList as $media) {
            $damAsset = new DamAsset($assetFamily, new Locale('en_US'), new ResourceType($media['type']));
            foreach ($media as $property => $value) {
                if (is_array($value)) {
                    foreach ($value as $subProperty => $subValue) {
                        $damAsset->addValue(sprintf('%s_%s', $property, (string) $subProperty), $subValue);
                    }
                }
            }

            $collection->addAsset($damAsset);
        }

        return $collection;
    }

    private function fetchMediaList(\DateTime $lastFetchDate, AssetFamily $assetFamily): array
    {
        $metaPropertyForFamiliesId = '753C2082-F36F-4AD3-9CBD2A238FDFC761';
        $metaPropertyForFamilies = $this->client->getAssetBankManager()->getMetaproperty($metaPropertyForFamiliesId)->wait();
        $damFamilies = $metaPropertyForFamilies['options'];

        $damFamilyId = '';
        foreach ($damFamilies as $damFamily) {
            if ($assetFamily->getCode() === $damFamily['name']) {
                $damFamilyId = $damFamily['id'];
                break;
            }
        }
        if ('' === $damFamilyId) {
            throw new \Exception(sprintf('Family "%" not found in Bynder.', $assetFamily->getCode()));
        }
        $mediaFilter = [
            'propertyOptionId' => $damFamilyId,
            'dateModified' => $lastFetchDate->format('c'),
        ];

        return $this->client->getAssetBankManager()->getMediaList($mediaFilter)->wait();
    }
}
