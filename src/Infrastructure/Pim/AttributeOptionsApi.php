<?php

declare(strict_types=1);

namespace AkeneoDAMConnector\Infrastructure\Pim;

use Akeneo\PimEnterprise\ApiClient\Api\AssetManager\AssetAttributeOptionApiInterface;
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
        $this->attributeOptions = new OptionsCollection();
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
     * @param Options[] $options
     */
    public function upsertAttributeOptions(array $options): void
    {
        foreach ($options as $option) {
            $pimOptions = $this->getAttributeOptionList($option->getFamilyCode(), $option->getAttributeCode());
            $diff = $pimOptions->getDiff($option);
            if (false !== $diff) {
                $this->attributeOptions->addOptions($diff);
                $this->pimStructure->addOptions($diff);
            }
        }

        if ($this->attributeOptions->count() >= self::BATCH_SIZE) {
            $this->flush();
        }
    }

    public function flush(?AssetFamilyCode $assetFamily = null): array
    {
        $optionsToUpsert = null === $assetFamily ?
            $this->attributeOptions->getOptions() :
            [(string) $assetFamily => $this->attributeOptions->getOptionsByFamily($assetFamily)];

        $responses = [];
        foreach ($optionsToUpsert as $familyCode => $optionsByFamily) {
            foreach ($optionsByFamily as $attributeCode => $options) {
                foreach ($options->normalize() as $normalizedOption) {
                    $optionCode = $normalizedOption['code'] ?? null;
                    if (null === $optionCode) {
                        continue;
                    }
                    $responses[$familyCode][$attributeCode][$optionCode] =
                        $this->api->upsert(
                            $familyCode,
                            $attributeCode,
                            $optionCode,
                            $normalizedOption
                        );
                }
            }
        }

        if (null === $assetFamily) {
            $this->attributeOptions = new OptionsCollection();
        } else {
            $this->attributeOptions->removeFamily($assetFamily);
        }

        return $responses;
    }
}
