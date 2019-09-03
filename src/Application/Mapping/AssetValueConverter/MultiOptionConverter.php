<?php

declare(strict_types=1);

namespace AkeneoDAMConnector\Application\Mapping\AssetValueConverter;

use AkeneoDAMConnector\Application\Mapping\AssetValueConverter;
use AkeneoDAMConnector\Domain\Asset\DamAsset;
use AkeneoDAMConnector\Domain\Asset\DamAssetValue;
use AkeneoDAMConnector\Domain\AssetAttribute;
use AkeneoDAMConnector\Domain\Asset\PimAssetValue;

class MultiOptionConverter implements AssetValueConverter
{
    public function getSupportedType(): string
    {
        return 'multiple_options';
    }

    public function convert(DamAsset $damAsset, DamAssetValue $damAssetValue, AssetAttribute $attribute): PimAssetValue
    {
        $options = array_map(
            function ($option) {
                return trim($option);
            },
            explode(',', $damAssetValue->value())
        );

        return new PimAssetValue($attribute, $options, (string)$damAsset->locale(), null);
    }
}

