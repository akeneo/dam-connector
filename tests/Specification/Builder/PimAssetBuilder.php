<?php
declare(strict_types=1);

namespace AkeneoDAMConnector\Tests\Specification\Builder;

use AkeneoDAMConnector\Domain\Model\Pim\PimAsset;
use AkeneoDAMConnector\Domain\Model\FamilyCode;

class PimAssetBuilder
{
    public static function build(string $code, string $assetFamilyCode, array $values = []): PimAsset
    {
        $familyCode = new FamilyCode($assetFamilyCode);

        return new PimAsset($code, $familyCode, $values);
    }
}
