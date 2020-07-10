<?php

declare(strict_types=1);

namespace PimApiTest\Infrastructure\Api;

use Akeneo\PimEnterprise\ApiClient\AkeneoPimEnterpriseClientInterface;
use PimApiTest\Infrastructure\Pim\ClientBuilder;

class ApiFamily
{
    /** @var AkeneoPimEnterpriseClientInterface */
    private $client;

    public function __construct(ClientBuilder $clientBuilder)
    {
        $this->client = $clientBuilder->getClient();
    }

    public function get(array $product): array
    {
        return $this->client->getFamilyApi()->get($product['family']);
    }

    public function getList(array $products): array
    {
        $families = [];
        foreach ($products as $product) {
            if (!array_key_exists($product['family'], $families)) {
                $families[$product['family']] = $this->get($product);
            }
        }

        return $families;
    }
}
