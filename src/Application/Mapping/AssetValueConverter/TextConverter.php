<?php

declare(strict_types=1);

namespace AkeneoDAMConnector\Application\Mapping\AssetValueConverter;

use AkeneoDAMConnector\Application\Mapping\AssetValueConverter;
use AkeneoDAMConnector\Domain\Asset\DamAsset;
use AkeneoDAMConnector\Domain\Asset\DamAssetValue;
use AkeneoDAMConnector\Domain\AssetAttribute;
use AkeneoDAMConnector\Domain\Asset\PimAssetValue;

class TextConverter implements AssetValueConverter
{
    public function getSupportedType(): string
    {
        return 'text';
    }

    public function convert(DamAsset $damAsset, DamAssetValue $damAssetValue, AssetAttribute $attribute): PimAssetValue
    {
        return new PimAssetValue($attribute, $damAssetValue->value(), (string)$damAsset->locale(), null);
    }
}

