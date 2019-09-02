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

namespace AkeneoDAMConnector\Infrastructure\Pim;

use AkeneoDAMConnector\Application\PimAdapter\UpdateAssetStructure;

/**
 * @author Romain Monceau <romain@akeneo.com>
 */
class UpdateAssetStructureApi implements UpdateAssetStructure
{
    private $assetAttributeApi;
    private $assetFamilyApi;

    public function __construct(ClientBuilder $clientBuilder)
    {
        $this->assetFamilyApi = $clientBuilder->getClient()->getAssetFamilyApi();
        $this->assetAttributeApi = $clientBuilder->getClient()->getAssetAttributeApi();
    }

    public function upsertAttribute(string $familyCode, string $attributeCode, array $data): void
    {
        $this->assetAttributeApi->upsert($familyCode, $attributeCode, $data);
    }

    public function upsertFamily(string $familyCode, array $data): void
    {
        $this->assetFamilyApi->upsert($familyCode, $data);
    }
}