<?php

declare(strict_types=1);

namespace AkeneoDAMConnector\Application\PimAdapter;

use AkeneoDAMConnector\Domain\Model\FamilyCode;
use AkeneoDAMConnector\Domain\Model\Pim\PimAsset;

interface UpdateAsset
{
    public function upsert(FamilyCode $assetFamilyCode, PimAsset $asset): void;

    public function flush(FamilyCode $assetFamilyCode): void;
}
