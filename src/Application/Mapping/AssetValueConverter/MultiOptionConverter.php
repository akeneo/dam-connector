<?php

declare(strict_types=1);

namespace AkeneoDAMConnector\Application\Mapping\AssetValueConverter;

use AkeneoDAMConnector\Application\Mapping\AssetValueConverterInterface;
use AkeneoDAMConnector\Domain\Asset\DamAsset;
use AkeneoDAMConnector\Domain\Asset\DamAssetValue;
use AkeneoDAMConnector\Domain\Pim\AssetAttribute;
use AkeneoDAMConnector\Domain\Pim\AssetValue;

class MultiOptionConverter implements AssetValueConverterInterface
{
    public function getSupportedType(): string
    {
        return 'multiple_options';
    }

    public function convert(DamAsset $damAsset, DamAssetValue $damAssetValue, AssetAttribute $attribute): AssetValue
    {
        $options = array_map(
            function ($option) {
                return trim($option);
            },
            explode(',', $damAssetValue->value())
        );

        return new AssetValue($attribute, $options, (string)$damAsset->locale(), null);
    }
}

