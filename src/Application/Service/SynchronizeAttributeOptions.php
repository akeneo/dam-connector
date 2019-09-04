<?php

declare(strict_types=1);

namespace AkeneoDAMConnector\Application\Service;

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

    public function __construct(GetAssetStructure $getAssetStructureApi, UpdateAssetStructure $updateAssetStructureApi)
    {
        $this->optionTypes = ['single_option', 'multiple_options'];
        $this->mapping = [
            'packshot' => [
                'sku' => new AssetAttribute('product_ref', 'text', false),
                'url' => new AssetAttribute('preview', 'media_link', false),
                'photograph' => new AssetAttribute('photograph', 'single_option', false),
                'country' => new AssetAttribute('country', 'single_option', false),
            ],
        ];
        $this->getAssetStructureApi = $getAssetStructureApi;
        $this->updateAssetStructureApi = $updateAssetStructureApi;
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
                $familyMapping = $this->mapping[$familyCode] ?? null;
                if (!$this->isSelectType($propertyCode, $familyMapping)) {
                    continue;
                }

                $optionsFromPim = $pimStructure[$familyCode][$propertyCode] ?? null;
                if (null === $optionsFromPim) {
                    $pimStructure[$familyCode][$propertyCode] = $optionsFromPim =
                        $this->retrieveAttributeOptionsFromPim(
                            $damAsset->assetFamilyCode(),
                            $familyMapping[$propertyCode]->getCode()
                        );
                }

                if ($this->isAlreadyInPimStructure($value->value(), $optionsFromPim) ||
                    $this->isAlreadyRegistered($value, $familyCode, $options)
                ) {
                    continue;
                }

                $options[$familyCode][$propertyCode][] = $value->value();
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

    private function isSelectType(string $propertyCode, ?array $familyMapping): bool
    {
        return null !== $familyMapping &&
            in_array($propertyCode, array_keys($familyMapping)) &&
            in_array($familyMapping[$propertyCode]->getType(), $this->optionTypes);
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
