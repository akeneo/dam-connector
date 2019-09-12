<?php

declare(strict_types=1);

namespace AkeneoDAMConnector\Infrastructure\Pim;

use Akeneo\PimEnterprise\ApiClient\Api\AssetManager\AssetAttributeOptionApiInterface;
use AkeneoDAMConnector\Domain\Asset\PimAssetValue;
use AkeneoDAMConnector\Domain\AssetAttributeCode;
use AkeneoDAMConnector\Domain\AssetFamilyCode;
use AkeneoDAMConnector\Domain\Options;
use AkeneoDAMConnector\Domain\OptionsCollection;

/**
 * @author Willy Mesnage <willy.mesnage@akeneo.com>
 */
class AttributeOptionsApi
{
    private const BATCH_SIZE = 100;

    /** @var AssetAttributeOptionApiInterface */
    private $api;

    /** @var OptionsCollection */
    private $attributeOptions;

    /** @var OptionsCollection */
    private $pimStructure;

    public function __construct(ClientBuilder $clientBuilder)
    {
        $this->api = $clientBuilder->getClient()->getAssetAttributeOptionApi();
        $this->attributeOptions = [];
        $this->pimStructure = new OptionsCollection();
    }

    public function getAttributeOptionList(
        AssetFamilyCode $familyCode,
        AssetAttributeCode $attributeCode
    ): Options
    {
        $pimOptions = $this->pimStructure->getOptionsByAttribute($familyCode, $attributeCode);

        if (null === $pimOptions) {
            $pimOptions = new Options($familyCode, $attributeCode);
            foreach ($this->api->all((string) $familyCode, (string) $attributeCode) as $option) {
                $pimOptions->addOption($option['code']);
            }
            $this->pimStructure->addOptions($pimOptions);
        }

        return $pimOptions;
    }

    /**
     * @param AssetFamilyCode $familyCode
     * @param PimAssetValue[] $options
     */
    public function upsertAttributeOptions(AssetFamilyCode $familyCode, array $assetValues): void
    {
        foreach ($assetValues as $value) {
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

    public function flush(AssetFamilyCode $assetFamily): void
    {
        foreach ($this->attributeOptions[(string) $assetFamily] as $attributeCode => $attributeOptions) {
            foreach ($attributeOptions as $attributeOption) {
                $toto = $this->api->upsert((string) $assetFamily, $attributeCode, $attributeOption, ['code' => $attributeOption]);
                var_dump($toto);
            }
        }
    }
}
