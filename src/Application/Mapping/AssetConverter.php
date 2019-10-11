<?php

declare(strict_types=1);

namespace AkeneoDAMConnector\Application\Mapping;

use AkeneoDAMConnector\Domain\Model\Dam\DamAsset;
use AkeneoDAMConnector\Domain\Model\Dam\DamAssetValue;
use AkeneoDAMConnector\Domain\Model\Pim\Attribute;
use AkeneoDAMConnector\Domain\Model\Pim\PimAssetValue;

class AssetConverter
{
    private $registry;

    public function __construct(AssetValueConverterRegistry $registry)
    {
        $this->registry = $registry;
    }

    public function convert(DamAsset $damAsset, DamAssetValue $damAssetValue, Attribute $attribute): PimAssetValue
    {
        return $this->registry->getConverter($attribute->getType())->convert(
            $damAsset,
            $damAssetValue,
            $attribute
        );
    }
}
