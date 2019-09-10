<?php

declare(strict_types=1);

/*
 * This file is part of the Akeneo PIM Enterprise Edition.
 *
 * (c) 2019 Akeneo SAS (http://www.akeneo.com)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AkeneoDAMConnector\Application\PimAdapter;


/**
 * @author Romain Monceau <romain@akeneo.com>
 */
interface UpdateAssetStructure
{
    public function upsertAttribute(string $familyCode, string $attributeCode, array $data): void;

    public function upsertFamily(string $familyCode, array $data): void;

    public function upsertAttributeOptions(
        string $familyCode,
        string $attributeCode,
        array $options
    ): array;
}
