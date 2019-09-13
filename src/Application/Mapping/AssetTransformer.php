<?php

declare(strict_types=1);

namespace AkeneoDAMConnector\Application\Mapping;

use AkeneoDAMConnector\Domain\Asset\DamAsset;
use AkeneoDAMConnector\Domain\Asset\PimAsset;
use AkeneoDAMConnector\Domain\Asset\PimAssetValue;

class AssetTransformer
{
    private $assetMapper;
    private $assetConverter;

    public function __construct(
        AssetMapper $assetMapper,
        AssetConverter $assetConverter
    ) {
        $this->assetMapper = $assetMapper;
        $this->assetConverter = $assetConverter;
    }

    public function damToPim(DamAsset $damAsset): PimAsset
    {
        /** @var string[] $damPropertyCodes */
        $damPropertyCodes = $this->assetMapper->getMappedProperties($damAsset->assetFamilyCode());

        /** @var PimAssetValue[] $pimAssetValues */
        $pimAssetValues = [];
        foreach ($damPropertyCodes as $damPropertyCode) {
            if (!isset($damAsset->getValues()[$damPropertyCode])) {
                continue;
            }
            $damAssetValue = $damAsset->getValues()[$damPropertyCode];

            $attribute = $this->assetMapper->mapAttribute(
                $damAsset->assetFamilyCode(),
                $damPropertyCode
            );

            $pimAssetValues[] = $this->assetConverter->convert($damAsset, $damAssetValue, $attribute);
        }

        return new PimAsset((string)$damAsset->damAssetIdentifier(), $damAsset->assetFamilyCode(), $pimAssetValues);
    }
}
