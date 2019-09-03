<?php

declare(strict_types=1);

namespace AkeneoDAMConnector\Application\Mapping\AssetValueConverter;

use AkeneoDAMConnector\Application\Mapping\AssetValueConverter;
use AkeneoDAMConnector\Domain\Asset\DamAsset;
use AkeneoDAMConnector\Domain\Asset\DamAssetValue;
use AkeneoDAMConnector\Domain\Asset\PimAssetValue;
use AkeneoDAMConnector\Domain\AssetAttribute;

class MediaLinkConverter implements AssetValueConverter
{
    public function getSupportedType(): string
    {
        return 'media_link';
    }

    public function convert(DamAsset $damAsset, DamAssetValue $damAssetValue, AssetAttribute $attribute): PimAssetValue
    {
        $locale = $attribute->isLocalizable() ? (string)$damAsset->locale() : null;

        return new PimAssetValue($attribute->getCode(), $damAssetValue->value(), $locale, null);
    }
}

