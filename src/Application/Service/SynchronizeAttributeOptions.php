<?php

declare(strict_types=1);

namespace AkeneoDAMConnector\Application\Service;

use AkeneoDAMConnector\Application\ConfigLoader;
use AkeneoDAMConnector\Application\PimAdapter\GetAssetStructure;
use AkeneoDAMConnector\Application\PimAdapter\UpdateAssetStructure;
use AkeneoDAMConnector\Domain\Asset\DamAssetCollection;
use AkeneoDAMConnector\Domain\Asset\DamAssetValue;
use AkeneoDAMConnector\Domain\AssetAttribute;
use AkeneoDAMConnector\Domain\AssetFamilyCode;

/**
 * @author Willy Mesnage <willy.mesnage@akeneo.com>
 */
class SynchronizeAttributeOptions
{
    /** @var array */
    private $mapping;

    /** @var array */
    private $optionTypes;

    /** @var GetAssetStructure */
    private $getAssetStructureApi;

    /** @var UpdateAssetStructure */
    private $updateAssetStructureApi;

    /** @var array */
    private $structureConfig;

    public function __construct(
        ConfigLoader $mappingLoader,
        ConfigLoader $structureConfigLoader,
        GetAssetStructure $getAssetStructureApi,
        UpdateAssetStructure $updateAssetStructureApi
    ) {
        $this->optionTypes = ['single_option', 'multiple_options'];
        $this->getAssetStructureApi = $getAssetStructureApi;
        $this->updateAssetStructureApi = $updateAssetStructureApi;
        $this->mapping = $mappingLoader->load();
        $this->structureConfig = $structureConfigLoader->load();
    }

    public function execute(DamAssetCollection $damAssets): array
    {

        $options = $this->getOptionsNotInPim($damAssets);

        return $this->upsertAttributeOptions($options);
    }

    private function getOptionsNotInPim(DamAssetCollection $damAssets): array
    {
        $options = [];
        $pimStructure = [];
        foreach ($damAssets as $damAsset) {
            foreach ($damAsset->getValues() as $value) {
                $familyCode = (string) $damAsset->assetFamilyCode();
                $propertyCode = $value->property();
                $familyMapping = $this->getFamilyMapping($familyCode);
                if (null === $familyMapping) {
                    continue;
                }
                $attributeCode = $this->convertToPimAttributeCode($familyMapping, $propertyCode);
                if (null === $attributeCode) {
                    continue;
                }
                if (!$this->isSelectType($familyCode, $attributeCode)) {
                    continue;
                }

                $optionsFromPim = $pimStructure[$familyCode][$attributeCode] ?? null;
                if (null === $optionsFromPim) {
                    $pimStructure[$familyCode][$attributeCode] = $optionsFromPim =
                        $this->retrieveAttributeOptionsFromPim(
                            $damAsset->assetFamilyCode(),
                            $attributeCode
                        );
                }

                if ($this->isAlreadyInPimStructure($value->value(), $optionsFromPim) ||
                    $this->isAlreadyRegistered($value, $familyCode, $options)
                ) {
                    continue;
                }

                $options[$familyCode][$attributeCode][] = $value->value();
            }
        }

        return $options;
    }

    private function upsertAttributeOptions(array $options): array
    {
        if (empty($options)) {
            return [];
        }

        $responseCodes = [];
        foreach ($options as $familyCode => $attributes) {
            foreach ($attributes as $attributeCode => $attributeOptions) {
                foreach ($attributeOptions as $attributeOption) {
                    $responseCodes[$familyCode][$attributeCode][$attributeOption] =
                        $this->updateAssetStructureApi->upsertAttributeOption(
                            $familyCode,
                            $attributeCode,
                            $attributeOption,
                            ['code' => $attributeOption]
                        );
                }
            }
        }

        return $responseCodes;
    }

    private function isSelectType(string $familyCode, string $attributeCode): bool
    {
        $familyStructure = $this->structureConfig[$familyCode] ?? null;

        return is_array($familyStructure) &&
            is_array($familyStructure['attributes']) &&
            !empty($familyStructure['attributes']) &&
            !empty(
                array_filter($familyStructure['attributes'], function ($attribute) use ($attributeCode) {
                    return $attribute['code'] === $attributeCode && in_array($attribute['type'], $this->optionTypes);
                })
            );
    }

    private function convertToPimAttributeCode(array $familyMapping, string $propertyCode): ?string
    {
        return $familyMapping[$propertyCode] ?? null;
    }

    private function getFamilyMapping(string $familyCode): ?array
    {
        return $this->mapping[$familyCode] ?? null;
    }

    private function isAlreadyInPimStructure(string $value, array $optionsFromPim): bool
    {
        return in_array($value, $optionsFromPim);
    }

    private function retrieveAttributeOptionsFromPim(AssetFamilyCode $familyCode, string $attributeCode): array
    {
        return $this->getAssetStructureApi->getAttributeOptionList($familyCode, $attributeCode);
    }

    private function isAlreadyRegistered(DamAssetValue $newValue, string $familyCode, array $registeredOptions): bool
    {
        $options = $registeredOptions[$familyCode][$newValue->property()] ?? null;

        return null !== $options ? in_array($newValue->value(), $options) : false;
    }
}
