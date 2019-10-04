<?php

declare(strict_types=1);

namespace AkeneoDAMConnector\Infrastructure\Pim;

use Akeneo\Pim\ApiClient\Exception\UnprocessableEntityHttpException;
use Akeneo\PimEnterprise\ApiClient\Api\AssetManager\AssetAttributeApiInterface;
use Akeneo\PimEnterprise\ApiClient\Api\AssetManager\AssetAttributeOptionApiInterface;
use Akeneo\PimEnterprise\ApiClient\Api\AssetManager\AssetFamilyApiInterface;
use AkeneoDAMConnector\Application\PimAdapter\UpdateAssetStructure;

/**
 * @author Romain Monceau <romain@akeneo.com>
 */
class UpdateAssetStructureApi implements UpdateAssetStructure
{
    /** @var AssetAttributeApiInterface */
    private $assetAttributeApi;

    /** @var AssetFamilyApiInterface */
    private $assetFamilyApi;

    /** @var AssetAttributeOptionApiInterface */
    private $assetAttributeOptionApi;

    public function __construct(ClientBuilder $clientBuilder)
    {
        $this->assetFamilyApi = $clientBuilder->getClient()->getAssetFamilyApi();
        $this->assetAttributeApi = $clientBuilder->getClient()->getAssetAttributeApi();
        $this->assetAttributeOptionApi = $clientBuilder->getClient()->getAssetAttributeOptionApi();
    }

    public function upsertAttribute(string $familyCode, string $attributeCode, array $data): void
    {
        // TODO: Try/catch done because an error http code is sent when no change on attribute
        try {
            $this->assetAttributeApi->upsert($familyCode, $attributeCode, $data);
        } catch (UnprocessableEntityHttpException $e) {
            foreach ($e->getResponseErrors() as $error) {
                if ($error['message'] !== 'There should be updates to perform on the attribute. None found.') {
                    throw $e;
                }
            }
        }
    }

    public function upsertFamily(string $familyCode, array $data): void
    {
        $this->assetFamilyApi->upsert($familyCode, $data);
    }
}
