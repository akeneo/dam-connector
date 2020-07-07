<?php

declare(strict_types=1);

namespace PimApiTest\Infrastructure\Command;

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

    /** @var ApiAttribute */
    private $apiAttribute;

    /** @var ApiAttributeOptions */
    private $apiAttributeOptions;

    /** @var ApiCategory */
    private $apiCategory;

    public function __construct(
        ApiProduct $productApi,
        ApiFamily $apiFamily,
        ApiCategory $apiCategory,
        ApiAttribute $apiAttribute,
        ApiAttributeOptions $apiAttributeOptions
    ) {
        parent::__construct(self::NAME);

        $this->productApi = $productApi;
        $this->apiFamily = $apiFamily;
        $this->apiAttributeOptions = $apiAttributeOptions;
        $this->apiCategory = $apiCategory;
        $this->apiAttribute = $apiAttribute;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $product = $this->productApi->get('1597232365353');
        $family = $this->apiFamily->get($product);
        $categories = $this->apiCategory->get($product);
        $attributes = $this->apiAttribute->get($product);
        $attributeOptions = $this->getAttributeOptionsFromProduct($product, $attributes);
    }

    private function getAttributeOptionsFromProduct(array $product, array $attributes): array
    {
        $attributeOptions = [];
        foreach ($product['values'] as $attributeCode => $values) {
            if (
                !in_array($attributes[$attributeCode]['type'], ['pim_catalog_simpleselect', 'pim_catalog_multiselect']) &&
                !array_key_exists($attributeCode, $attributeOptions)
            ) {
                continue;
            }
            $attributeOptions[$attributeCode] = $this->apiAttributeOptions->all((string) $attributeCode);
        }

        return $attributeOptions;
    }
}
