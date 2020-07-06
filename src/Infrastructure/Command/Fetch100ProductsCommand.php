<?php

declare(strict_types=1);

namespace PimApiTest\Infrastructure\Command;

use PimApiTest\Infrastructure\Api\ApiFamily;
use PimApiTest\Infrastructure\Api\ApiProduct;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Fetch100ProductsCommand extends Command
{
    const NAME = 'api:fetch-100-products';

    /** @var ApiProduct */
    private $productApi;

    /** @var ApiFamily */
    private $apiFamily;

    public function __construct(ApiProduct $productApi, ApiFamily $apiFamily)
    {
        parent::__construct(self::NAME);

        $this->productApi = $productApi;
        $this->apiFamily = $apiFamily;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // $product = $this->productApi->get('1597232365353');

        // echo json_encode($family, JSON_PRETTY_PRINT);
    }
}
