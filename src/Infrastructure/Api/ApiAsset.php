<?php

namespace PimApiTest\Infrastructure\Api;

use Akeneo\PimEnterprise\ApiClient\AkeneoPimEnterpriseClientInterface;
use PimApiTest\Infrastructure\Pim\ClientBuilder;

class ApiAsset
{
    const ASSET_COLLECTION = 'pim_catalog_asset_collection';

    /** @var AkeneoPimEnterpriseClientInterface */
    private $client;

    public function __construct(ClientBuilder $clientBuilder)
    {
        $this->client = $clientBuilder->getClient();
    }

    public function get(string $assetFamily, string $assetCode): array
    {
        $assetAttributes = [];

        foreach ($this->client->getAssetManagerApi()->get($assetFamily, $assetCode) as $attribute) {
            $assetAttributes[] = $attribute;
        }
        return $assetAttributes;
    }

    public function getAssetsPerFamily(array $product, array $attributes): array
    {
        $assetsPerFamily = [];

        foreach ($product['values'] as $attributeCode => $attributeValues) {
            if (!isset($attributes[$attributeCode]) || $attributes[$attributeCode]['type'] !== self::ASSET_COLLECTION) {
                continue;
            }

            $assetFamilyCode = $attributes[$attributeCode]['reference_data_name'];
            if (!isset($assetsPerFamily[$assetFamilyCode])) {
                $assetsPerFamily[$assetFamilyCode] = [];
            }

            foreach ($attributeValues as $value) {
                foreach ($value['data'] as $assetCode) {
                    $assetsPerFamily[$assetFamilyCode][$assetCode] = $this->get($assetFamilyCode, $assetCode);
                }
            }
        }

        return $assetsPerFamily;
    }
}
