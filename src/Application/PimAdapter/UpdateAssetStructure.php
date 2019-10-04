<?php

declare(strict_types=1);

namespace AkeneoDAMConnector\Application\PimAdapter;

/**
 * @author Romain Monceau <romain@akeneo.com>
 */
interface UpdateAssetStructure
{
    public function upsertAttribute(string $familyCode, string $attributeCode, array $data): void;

    public function upsertFamily(string $familyCode, array $data): void;
}
