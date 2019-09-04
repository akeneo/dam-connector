<?php

declare(strict_types=1);

namespace AkeneoDAMConnector\Application\Mapping\AssetValueConverter;

use AkeneoDAMConnector\Application\Mapping\AssetValueConverter;
use AkeneoDAMConnector\Domain\Asset\DamAsset;
use AkeneoDAMConnector\Domain\Asset\DamAssetValue;
use AkeneoDAMConnector\Domain\AssetAttribute;
use AkeneoDAMConnector\Domain\Asset\PimAssetValue;

class SingleOptionConverter implements AssetValueConverter
{
    public function getSupportedType(): string
    {
        return 'single_option';
    }

    public function convert(DamAsset $damAsset, DamAssetValue $damAssetValue, AssetAttribute $attribute): PimAssetValue
    {
        $locale = $attribute->isLocalizable() ? (string)$damAsset->locale() : null;

        return new PimAssetValue($attribute, $damAssetValue->value(), $locale, null);
    }
}

