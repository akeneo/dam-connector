<?php

declare(strict_types=1);

namespace AkeneoDAMConnector\Application\Mapping;

use AkeneoDAMConnector\Domain\Asset\DamAsset;
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
                'colors' => new AssetAttribute('colors', 'multiple_options', false),
                'country' => new AssetAttribute('country', 'single_option', false),
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

        /** @var AssetValue[] $pimAssetValues */
        $pimAssetValues = [];
        foreach ($damAsset->getValues() as $damAssetValue) {
            if (!isset($assetFamilyMapping[$damAssetValue->property()])) {
                continue;
            }

            /** @var AssetAttribute $assetAttribute */
            $assetAttribute = $assetFamilyMapping[$damAssetValue->property()];

            $pimAssetValues[] = $this->converterRegistry->getConverter($assetAttribute->getType())->convert(
                $damAsset,
                $damAssetValue,
                $assetAttribute
            );
        }

        return new Asset((string)$damAsset->damAssetIdentifier(), $pimAssetValues);
    }
}
