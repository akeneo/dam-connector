<?php

declare(strict_types=1);

namespace AkeneoDAMConnector\Infrastructure\Pim;

use AkeneoDAMConnector\Domain\AssetFamily;
use AkeneoDAMConnector\Application\AssetFamilyApiInterface;

class AssetFamilyApi implements AssetFamilyApiInterface
{
    /** @var AssetFamilyApiInterface */
    private $api;

    public function __construct(ClientBuilder $clientBuilder)
    {
        $this->api = $clientBuilder->getClient()->getAssetFamilyApi();
    }

    public function getAssetFamily(string $familyCode): AssetFamily
    {
        $response = $this->api->get($familyCode);

        return new AssetFamily($response['code'], $response['labels']);
    }

    public function listAssetFamilies(): array
    {
        $assetFamilies = $this->api->all();
        $families = [];
        foreach ($assetFamilies as $family) {
            $families[] = new AssetFamily($family['code'], $family['labels']);
        }

        return $families;
    }
}
