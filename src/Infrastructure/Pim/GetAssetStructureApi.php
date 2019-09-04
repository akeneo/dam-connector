<?php

declare(strict_types=1);

namespace AkeneoDAMConnector\Infrastructure\Pim;

use Akeneo\PimEnterprise\ApiClient\Api\AssetManager\AssetAttributeOptionApiInterface;
use AkeneoDAMConnector\Application\PimAdapter\GetAssetStructure;
use AkeneoDAMConnector\Domain\AssetFamilyCode;

/**
 * @author Willy Mesnage <willy.mesnage@akeneo.com>
 */
class GetAssetStructureApi implements GetAssetStructure
{
    /** @var AssetAttributeOptionApiInterface */
    private $api;

    public function __construct(ClientBuilder $clientBuilder)
    {
        $this->api = $clientBuilder->getClient()->getAssetAttributeOptionApi();
    }

    public function getAttributeOptionList(AssetFamilyCode $familyCode, string $attributeCode): array
    {
        return $this->api->all((string) $familyCode, $attributeCode);
    }
}
