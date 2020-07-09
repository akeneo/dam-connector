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

    public function getAssets(array $product, array $attributes): array
    {
        $assets = [];

        foreach ($product['values'] as $attributeCode => $attributeValue) {
            if (!isset($attributes[$attributeCode]) || $attributes[$attributeCode]['type'] !== self::ASSET_COLLECTION) {
                continue;
            }

            $assetFamily = $attributes[$attributeCode]['reference_data_name'];
            foreach ($attributeValue as $assetCode) {
                $assets[$assetCode] = $this->apiAssetAttribute->get($assetFamily, $assetCode);
            }
        }

        return $assets;
    }
}
