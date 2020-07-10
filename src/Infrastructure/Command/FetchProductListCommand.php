<?php

declare(strict_types=1);

namespace PimApiTest\Infrastructure\Command;

use PimApiTest\Infrastructure\Api\ApiAttribute;
use PimApiTest\Infrastructure\Api\ApiFamily;
use PimApiTest\Infrastructure\Api\ApiProduct;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FetchProductListCommand extends Command
{
    const NAME = 'api:fetch-product-list';

    /** @var ApiProduct */
    private $productApi;

    /** @var ApiFamily */
    private $apiFamily;

    /** @var ApiAttribute */
    private $apiAttribute;

    public function __construct(ApiProduct $productApi, ApiFamily $apiFamily, ApiAttribute $apiAttribute)
    {
        parent::__construct(self::NAME);

        $this->productApi = $productApi;
        $this->apiFamily = $apiFamily;
        $this->apiAttribute = $apiAttribute;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /**
         * Categrory Tree:
         * Sales (888063)
         *   -> 2306815041343 (153559)
         *     -> 6200928056040 (20120)
         *       -> 9500806876911 (1656)
         *         -> 9539300120343 (155)
         * @var string
         */
        $categoryCode = '9539300120343';
        $numberOfExpectedProducts = 155;

        $products = $this->productApi->getList($categoryCode, $numberOfExpectedProducts);
        $families = $this->apiFamily->getList($products);
        $attributes = $this->apiAttribute->get();
    }
}
