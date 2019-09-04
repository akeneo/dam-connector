<?php

declare(strict_types=1);

namespace AkeneoDAMConnector\Application\PimAdapter;

use AkeneoDAMConnector\Domain\AssetFamilyCode;

/**
 * @author Willy Mesnage <willy.mesnage@akeneo.com>
 */
interface GetAssetStructure
{
    public function getAttributeOptionList(AssetFamilyCode $familyCode, string $attributeCode): array;
}
