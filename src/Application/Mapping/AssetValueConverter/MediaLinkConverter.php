<?php

declare(strict_types=1);

namespace AkeneoDAMConnector\Application\Mapping\AssetValueConverter;

use AkeneoDAMConnector\Application\Mapping\AssetValueConverterInterface;
use AkeneoDAMConnector\Domain\Asset\DamAsset;
use AkeneoDAMConnector\Domain\Asset\DamAssetValue;
use AkeneoDAMConnector\Domain\Pim\AssetAttribute;
use AkeneoDAMConnector\Domain\Pim\AssetValue;

class MediaLinkConverter implements AssetValueConverterInterface
{
    public function getSupportedType(): string
    {
        return 'media_link';
    }

    public function convert(DamAsset $damAsset, DamAssetValue $damAssetValue, AssetAttribute $attribute): AssetValue
    {
        return new AssetValue($attribute, $damAssetValue->value(), (string)$damAsset->locale(), null);
    }
}

