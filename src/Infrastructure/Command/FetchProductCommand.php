<?php

declare(strict_types=1);

namespace PimApiTest\Infrastructure\Command;

use PimApiTest\Infrastructure\Api\ApiCategory;
use PimApiTest\Infrastructure\Api\ApiFamily;
use PimApiTest\Infrastructure\Api\ApiProduct;
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

    public function __construct(
        ApiProduct $productApi,
        ApiFamily $apiFamily,
        ApiCategory $apiCategory
    ) {
        parent::__construct(self::NAME);

        $this->productApi = $productApi;
        $this->apiFamily = $apiFamily;
        $this->apiCategory = $apiCategory;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $product = $this->productApi->get('1597232365353');
        $family = $this->apiFamily->get($product);
        $categories = $this->apiCategory->get($product);
    }
}
