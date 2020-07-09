<?php

declare(strict_types=1);

namespace PimApiTest\Infrastructure\Command;

use PimApiTest\Infrastructure\Api\ApiAsset;
use PimApiTest\Infrastructure\Api\ApiCategory;
use PimApiTest\Infrastructure\Api\ApiAttributeOptions;
use PimApiTest\Infrastructure\Api\ApiFamily;
use PimApiTest\Infrastructure\Api\ApiProduct;
use PimApiTest\Infrastructure\Api\ApiAttribute;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FetchProductCommand extends Command
{
    const NAME = 'api:fetch-product';

    /** @var ApiProduct */
    private $productApi;

    /** @var ApiFamily */
    private $apiFamily;

    /** @var ApiCategory */
    private $apiCategory;

    /** @var ApiAttribute */
    private $apiAttribute;

    /** @var ApiAttributeOptions */
    private $apiAttributeOptions;

    /** @var ApiAsset */
    private $apiAsset;

    public function __construct(
        ApiProduct $productApi,
        ApiFamily $apiFamily,
        ApiCategory $apiCategory,
        ApiAttribute $apiAttribute,
        ApiAttributeOptions $apiAttributeOptions,
        ApiAsset $apiAsset
    ) {
        parent::__construct(self::NAME);

        $this->productApi = $productApi;
        $this->apiFamily = $apiFamily;
        $this->apiCategory = $apiCategory;
        $this->apiAttribute = $apiAttribute;
        $this->apiAttributeOptions = $apiAttributeOptions;
        $this->apiAsset = $apiAsset;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $product = $this->productApi->get('1597232365353');
        $family = $this->apiFamily->get($product);
        $categories = $this->apiCategory->get($product);
        $attributes = $this->apiAttribute->get($product);
        $attributeOptions = $this->apiAttributeOptions->getAttributeOptionsFromProduct($product, $attributes);
        $assets = $this->apiAsset->getAssets($product, $attributes);

        echo json_encode($assets, JSON_PRETTY_PRINT);
    }
}
