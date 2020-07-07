<?php

declare(strict_types=1);

namespace PimApiTest\Infrastructure\Api;

use Akeneo\PimEnterprise\ApiClient\AkeneoPimEnterpriseClientInterface;
use PimApiTest\Infrastructure\Pim\ClientBuilder;

class ApiProduct
{
    /** @var AkeneoPimEnterpriseClientInterface */
    private $client;

    public function __construct(ClientBuilder $clientBuilder)
    {
        $this->client = $clientBuilder->getClient();
    }

    public function get(string $identifier): array
    {
        return $this->client->getProductApi()->get($identifier);
    }

    public function getList(string $categoryCode, int $numberOfExpectedProducts): array
    {
        $iterator = $this->client->getProductApi()->all(100, [
            'search' => [
                'categories' => [
                    ['operator' => 'IN', 'value' => [$categoryCode]]
                ],
            ]
        ]);

        $products = [];
        $count = 0;
        foreach ($iterator as $product) {
            $products[] = $product;
            if (++$count > $numberOfExpectedProducts) {
                throw new \RuntimeException(sprintf(
                    'Too much products. The category "%s" should have %d products.',
                    $categoryCode,
                    $numberOfExpectedProducts
                ));
            }
        }
        if ($count < $numberOfExpectedProducts) {
            throw new \RuntimeException(sprintf(
                '%d products received. The category "%s" should have %d products.',
                $count,
                $categoryCode,
                $numberOfExpectedProducts
            ));
        }

        return $products;
    }
}
