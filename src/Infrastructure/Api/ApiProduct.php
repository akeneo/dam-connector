<?php

declare(strict_types=1);

namespace PimApiTest\Infrastructure\Api;

use Akeneo\PimEnterprise\ApiClient\AkeneoPimEnterpriseClientInterface;
use PimApiTest\Infrastructure\Pim\ClientBuilder;

class ApiProduct
{
    const ASSET_COLLECTION = 'pim_catalog_asset_collection';

    /** @var AkeneoPimEnterpriseClientInterface */
    private $client;

    /** @var ApiAssetAttribute */
    private $apiAssetAttribute;

    public function __construct(ClientBuilder $clientBuilder, ApiAssetAttribute $apiAssetAttribute)
    {
        $this->client = $clientBuilder->getClient();
        $this->apiAssetAttribute = $apiAssetAttribute;
    }

    public function get(string $identifier): array
    {
        return $this->client->getProductApi()->get($identifier);
    }

    public function filterAttributesOnType(array $attributes, string $type): array
    {
        return array_filter($attributes, function ($att) use ($type) {
            return $att['type'] === $type;
        });
    }

    public function getAssetAttributes(array $product, array $attributes): array
    {
        $assetAttributes = [];

        foreach ($this->filterAttributesOnType($attributes, self::ASSET_COLLECTION) as $assetCollectionAttributes) {

            $assetCode = $assetCollectionAttributes['code'];
            $assetFamily = $assetCollectionAttributes['reference_data_name'];

            if (array_key_exists($assetCode, $product['values'])) {
                $assetAttributes[$assetCode] = [];

                foreach ($product['values'][$assetCode][0]['data'] as $asset) {
                    $assetAttributes[$assetCode][] = $this->apiAssetAttribute->get($assetFamily, $asset);
                }
            }
        }

        return $assetAttributes;
    }

}
