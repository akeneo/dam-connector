<?php

declare(strict_types=1);

namespace AkeneoDAMConnector\Application\Mapping\AssetValueConverter;

use AkeneoDAMConnector\Application\Mapping\AssetValueConverter;
use AkeneoDAMConnector\Domain\Model\Dam\DamAsset;
use AkeneoDAMConnector\Domain\Model\Dam\DamAssetValue;
use AkeneoDAMConnector\Domain\Model\Pim\Attribute;
use AkeneoDAMConnector\Domain\Model\Pim\PimAssetValue;

class SingleOptionConverter implements AssetValueConverter
{
    public function getSupportedType(): string
    {
        return 'single_option';
    }

    public function convert(DamAsset $damAsset, DamAssetValue $damAssetValue, Attribute $attribute): PimAssetValue
    {
        $locale = $attribute->isLocalizable() ? (string)$damAsset->locale() : null;

        return new PimAssetValue($attribute, $damAssetValue->value(), $locale, null);
    }
}
