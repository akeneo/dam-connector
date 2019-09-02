<?php

declare(strict_types=1);

namespace AkeneoDAMConnector\Application\Mapping;

use AkeneoDAMConnector\Domain\Asset\DamAsset;
use AkeneoDAMConnector\Domain\Asset\DamAssetValue;
use AkeneoDAMConnector\Domain\Pim\AssetAttribute;
use AkeneoDAMConnector\Domain\Pim\AssetValue;

interface AssetValueConverterInterface
{
    public function getSupportedType(): string;

    public function convert(DamAsset $damAsset, DamAssetValue $asset, AssetAttribute $attribute): AssetValue;
}
