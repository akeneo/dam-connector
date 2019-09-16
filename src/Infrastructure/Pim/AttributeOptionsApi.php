<?php

declare(strict_types=1);

namespace AkeneoDAMConnector\Infrastructure\Pim;

use Akeneo\PimEnterprise\ApiClient\Api\AssetManager\AssetAttributeOptionApiInterface;
use AkeneoDAMConnector\Domain\AssetFamilyCode;

/**
 * @author Willy Mesnage <willy.mesnage@akeneo.com>
 */
class AttributeOptionsApi
{
    /** @var AssetAttributeOptionApiInterface */
    private $api;

    /** @var array */
    private $attributeOptions;

    /** @var array */
    private $pimStructure;

    public function __construct(ClientBuilder $clientBuilder)
    {
        $this->api = $clientBuilder->getClient()->getAssetAttributeOptionApi();
        $this->attributeOptions = [];
        $this->pimStructure = [];
    }

    public function upsertAttributeOptions(AssetFamilyCode $familyCode, array $assetValues): void
    {
        if (!isset($this->attributeOptions[(string) $familyCode])) {
            $this->attributeOptions[(string) $familyCode] = [];
        }

        foreach ($assetValues as $value) {
            if (!isset($this->attributeOptions[(string) $familyCode][(string) $value->getAttributeCode()])) {
                $this->attributeOptions[(string) $familyCode][(string) $value->getAttributeCode()] = [];
            }

            if (is_array($value->getData())) {
                $this->attributeOptions[(string) $familyCode][(string) $value->getAttributeCode()] =
                    array_merge(
                        $this->attributeOptions[(string) $familyCode][(string) $value->getAttributeCode()],
                        $value->getData()
                    );
            } else {
                $this->attributeOptions[(string) $familyCode][(string) $value->getAttributeCode()][] = $value->getData();
            }
        }
    }

    public function flush(AssetFamilyCode $familyCode): void
    {
        foreach ($this->attributeOptions[(string) $familyCode] as $attributeCode => $attributeOptions) {
            $pimOptions = $this->get((string) $familyCode, $attributeCode);
            $optionsToUpsert = array_diff(array_unique($attributeOptions), $pimOptions);
            foreach ($optionsToUpsert as $attributeOption) {
                $this->api->upsert((string) $familyCode, $attributeCode, $attributeOption, ['code' => $attributeOption]);
            }
        }

        unset($this->attributeOptions[(string) $familyCode]);
    }

    public function get(string $familyCode, string $attributeCode): array
    {
        if (array_key_exists($familyCode, $this->pimStructure) &&
            array_key_exists($attributeCode, $this->pimStructure[$familyCode])
        ) {
            return $this->pimStructure[$familyCode][$attributeCode];
        }

        $pimOptions = $this->api->all((string) $familyCode, (string) $attributeCode);

        $this->pimStructure[$familyCode][$attributeCode] = array_reduce(
            $pimOptions,
            function ($carry, $option) {
                $carry[] = $option['code'];

                return $carry;
            },
            []
        );

        return $this->pimStructure[$familyCode][$attributeCode];
    }
}
