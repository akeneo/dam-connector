<?php

declare(strict_types=1);

namespace AkeneoDAMConnector\Application\Mapping;

use AkeneoDAMConnector\Domain\Asset\DamAsset;
use AkeneoDAMConnector\Domain\Asset\DamAssetValue;
use AkeneoDAMConnector\Domain\Pim\Asset;
use AkeneoDAMConnector\Domain\Pim\AssetAttribute;
use AkeneoDAMConnector\Domain\Pim\AssetValue;

class AssetTransformer
{
    private $converterRegistry;

    private $mapping;

    public function __construct(AssetValueConverterRegistry $converterRegistry)
    {
        $this->converterRegistry = $converterRegistry;

        $this->mapping = [
            'packshot' => [
                'sku' => new AssetAttribute('product_sku', 'text', false),
                'url' => new AssetAttribute('preview', 'media_link', false),
                'colors' => new AssetAttribute('colors', 'multiple_options', false)
            ],
        ];
    }

    public function damToPim(DamAsset $damAsset): Asset
    {
        $pimAssetFamily = $damAsset->assetFamily();
        if (null === $assetFamilyMapping = $this->mapping[$pimAssetFamily->getCode()]) {
            throw new \RuntimeException(
                sprintf('No mapping for asset family "%s" defined.', $pimAssetFamily->getCode())
            );
        }

        $filteredDamAssetValues = array_filter(
            $damAsset->getValues(),
            function (DamAssetValue $damAssetValue) use ($assetFamilyMapping) {
                return isset($assetFamilyMapping[$damAssetValue->property()]);
            }
        );

        /** @var AssetValue[] $pimAssetValues */
        $pimAssetValues = array_map(
            function (DamAssetValue $damAssetValue) use ($assetFamilyMapping, $damAsset) {
                /** @var AssetAttribute $assetAttribute */
                $assetAttribute = $assetFamilyMapping[$damAssetValue->property()];

                return $this->converterRegistry->getConverter($assetAttribute->getType())->convert(
                    $damAsset,
                    $damAssetValue,
                    $assetAttribute
                );
            },
            $filteredDamAssetValues
        );

        return new Asset((string)$damAsset->damAssetIdentifier(), $pimAssetValues);
    }
}
