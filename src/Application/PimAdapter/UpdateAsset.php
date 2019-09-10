<?php

declare(strict_types=1);

namespace AkeneoDAMConnector\Application\PimAdapter;

use AkeneoDAMConnector\Domain\Asset\PimAsset;
use AkeneoDAMConnector\Domain\AssetFamilyCode;

interface UpdateAsset
{
    public function upsert(AssetFamilyCode $assetFamilyCode, PimAsset $asset): void;

    public function flush(AssetFamilyCode $assetFamilyCode): void;
}
