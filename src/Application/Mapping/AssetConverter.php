<?php

declare(strict_types=1);

namespace AkeneoDAMConnector\Application\Mapping;

use AkeneoDAMConnector\Domain\Asset\DamAsset;
use AkeneoDAMConnector\Domain\Asset\DamAssetValue;
use AkeneoDAMConnector\Domain\Asset\PimAssetValue;
use AkeneoDAMConnector\Domain\AssetAttribute;

class AssetConverter
{
    private $registry;

    public function __construct(AssetValueConverterRegistry $registry)
    {
        $this->registry = $registry;
    }

    public function convert(DamAsset $damAsset, DamAssetValue $damAssetValue, AssetAttribute $attribute): PimAssetValue
    {
        return $this->registry->getConverter($attribute->getType())->convert(
            $damAsset,
            $damAssetValue,
            $attribute
        );
    }
}
