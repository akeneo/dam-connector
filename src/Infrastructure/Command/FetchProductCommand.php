<?php

declare(strict_types=1);

namespace PimApiTest\Infrastructure\Command;

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


    public function __construct(ApiProduct $productApi, ApiFamily $apiFamily, ApiAttribute $apiAttribute)
    {
        parent::__construct(self::NAME);

        $this->productApi = $productApi;
        $this->apiFamily = $apiFamily;
        $this->apiAttribute = $apiAttribute;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $product = $this->productApi->get('1597232365353');
        $family = $this->apiFamily->get($product);
        $attributes = $this->apiAttribute->get($product);
    }
}
