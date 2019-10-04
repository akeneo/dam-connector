<?php

declare(strict_types=1);

namespace AkeneoDAMConnector\Application\Mapping\AssetValueConverter;

use AkeneoDAMConnector\Application\Mapping\AssetValueConverter;
use AkeneoDAMConnector\Domain\Model\Dam\DamAsset;
use AkeneoDAMConnector\Domain\Model\Dam\DamAssetValue;
use AkeneoDAMConnector\Domain\Model\Pim\Attribute;
use AkeneoDAMConnector\Domain\Model\Pim\PimAssetValue;

class MultiOptionConverter implements AssetValueConverter
{
    public function getSupportedType(): string
    {
        return 'multiple_options';
    }

    public function convert(
        DamAsset $damAsset,
        DamAssetValue $damAssetValue,
        Attribute $attribute
    ): PimAssetValue {
        $options = array_map(
            function ($option) {
                return trim($option);
            },
            explode(',', $damAssetValue->value())
        );

        $locale = $attribute->isLocalizable() ? (string)$damAsset->locale() : null;

        return new PimAssetValue($attribute, $options, $locale, null);
    }
}
