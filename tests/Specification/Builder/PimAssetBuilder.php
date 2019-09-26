<?php
declare(strict_types=1);

namespace AkeneoDAMConnector\Tests\Specification\Builder;

use AkeneoDAMConnector\Domain\Asset\PimAsset;
use AkeneoDAMConnector\Domain\AssetFamilyCode;

class PimAssetBuilder
{
    public static function build(string $code, string $assetFamilyCode, array $values = []): PimAsset
    {
        $familyCode = new AssetFamilyCode($assetFamilyCode);

        return new PimAsset($code, $familyCode, $values);
    }
}
