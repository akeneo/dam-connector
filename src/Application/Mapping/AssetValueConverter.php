<?php

declare(strict_types=1);

namespace AkeneoDAMConnector\Application\Mapping;

use AkeneoDAMConnector\Domain\Model\Dam\DamAsset;
use AkeneoDAMConnector\Domain\Model\Dam\DamAssetValue;
use AkeneoDAMConnector\Domain\Model\Pim\PimAssetValue;
use AkeneoDAMConnector\Domain\Model\Pim\Attribute;

interface AssetValueConverter
{
    public function getSupportedType(): string;

    public function convert(DamAsset $damAsset, DamAssetValue $asset, Attribute $attribute): PimAssetValue;
}
