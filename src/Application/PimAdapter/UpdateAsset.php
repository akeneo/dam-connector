<?php

declare(strict_types=1);

namespace AkeneoDAMConnector\Application\PimAdapter;

use AkeneoDAMConnector\Domain\Model\Pim\PimAsset;
use AkeneoDAMConnector\Domain\Model\FamilyCode;

interface UpdateAsset
{
    public function upsert(FamilyCode $assetFamilyCode, PimAsset $asset): void;

    public function flush(FamilyCode $assetFamilyCode): void;
}
