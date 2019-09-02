<?php

declare(strict_types=1);

namespace AkeneoDAMConnector\Application\Mapping;

use AkeneoDAMConnector\Domain\Asset\DamAsset;
use AkeneoDAMConnector\Domain\Asset\DamAssetValue;
use AkeneoDAMConnector\Domain\AssetAttribute;
use AkeneoDAMConnector\Domain\Asset\PimAssetValue;

interface AssetValueConverterInterface
{
    public function getSupportedType(): string;

    public function convert(DamAsset $damAsset, DamAssetValue $asset, AssetAttribute $attribute): PimAssetValue;
}
