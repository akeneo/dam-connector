<?php

declare(strict_types=1);

namespace PimApiTest\Infrastructure\Command;

use PimApiTest\Infrastructure\Api\ApiCategory;
use PimApiTest\Infrastructure\Api\ApiFamily;
use PimApiTest\Infrastructure\Api\ApiProduct;
use PimApiTest\Infrastructure\Api\ApiAttribute;
use PimApiTest\Infrastructure\Api\ApiAssetAttribute;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FetchProductCommand extends Command
{
    const NAME = 'api:fetch-product';
    const ASSET_COLLECTION = 'pim_catalog_asset_collection';

    /** @var ApiProduct */
    private $productApi;

    /** @var ApiFamily */
    private $apiFamily;

    /** @var ApiAttribute */
    private $apiAttribute;

    /** @var ApiCategory */
    private $apiCategory;

    /** @var @var ApiAssetAttribute */
    private $apiAssetAttribute;

    public function __construct(
        ApiProduct $productApi,
        ApiFamily $apiFamily,
        ApiCategory $apiCategory,
        ApiAttribute $apiAttribute,
        ApiAssetAttribute $apiAssetAttribute
    )
    {
        parent::__construct(self::NAME);

        $this->productApi = $productApi;
        $this->apiFamily = $apiFamily;
        $this->apiCategory = $apiCategory;
        $this->apiAttribute = $apiAttribute;
        $this->apiAssetAttribute = $apiAssetAttribute;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $product = $this->productApi->get('1597232365353');
        $family = $this->apiFamily->get($product);
        $categories = $this->apiCategory->get($product);
        $attributes = $this->apiAttribute->get($product);
        $assetCollectionAttributes = $this->filterAttributesOnType($attributes, self::ASSET_COLLECTION);
        $assetAttributes = $this->getAssetAttributes($product, $attributes);
    }

    private function filterAttributesOnType(array $attributes, string $type): array
    {
        return array_filter($attributes, function ($att) use ($type) {
            return $att['type'] === $type;
        });
    }

    private function getAssetAttributes(array $product, array $attributes): array
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
