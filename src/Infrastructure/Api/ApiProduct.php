<?php

declare(strict_types=1);

namespace PimApiTest\Infrastructure\Api;

use Akeneo\PimEnterprise\ApiClient\AkeneoPimEnterpriseClientInterface;
use PimApiTest\Infrastructure\Pim\ClientBuilder;

class ApiProduct
{
    const ASSET_COLLECTION = 'pim_catalog_asset_collection';

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

    public function getAssetAttributes(array $attributes) : array {
        return array_filter($attributes, function($att) {
            return $att['type'] === self::ASSET_COLLECTION;
        });
    }
}
