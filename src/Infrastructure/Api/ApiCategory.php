<?php

declare(strict_types=1);

namespace PimApiTest\Infrastructure\Api;

use Akeneo\PimEnterprise\ApiClient\AkeneoPimEnterpriseClientInterface;
use PimApiTest\Infrastructure\Pim\ClientBuilder;

class ApiCategory
{
    /** @var AkeneoPimEnterpriseClientInterface */
    private $client;

    public function __construct(ClientBuilder $clientBuilder)
    {
        $this->client = $clientBuilder->getClient();
    }

    public function get(array $product): array
    {
        $categories = [];

        foreach ($product['categories'] as $category) {
            $categories[] = $this->client->getCategoryApi()->get($category);
        }

        return $categories;
    }
}
