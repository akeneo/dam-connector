<?php

declare(strict_types=1);

namespace AkeneoDAMConnector\Application;

use AkeneoDAMConnector\Domain\AssetFamily;

interface AssetFamilyApiInterface
{
    public function getAssetFamily(string $familyCode): AssetFamily;

    public function listAssetFamilies(): array;
}
