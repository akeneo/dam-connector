<?php

declare(strict_types=1);

namespace AkeneoDAMConnector\Application\Mapping;

use AkeneoDAMConnector\Application\ConfigLoader;
use AkeneoDAMConnector\Domain\Model\Pim\Attribute;
use AkeneoDAMConnector\Domain\Model\Pim\AttributeCode;
use AkeneoDAMConnector\Domain\Model\FamilyCode;
use AkeneoDAMConnector\Domain\Exception\AttributeMappingNotFound;
use AkeneoDAMConnector\Domain\Exception\FamilyMappingNotFound;

class AssetMapper
{
    private $mappingConfigLoader;
    private $mappingConfig;

    private $assetAttributeBuilder;

    public function __construct(ConfigLoader $mappingConfigLoader, AssetAttributeBuilder $assetAttributeBuilder)
    {
        $this->mappingConfigLoader = $mappingConfigLoader;
        $this->assetAttributeBuilder = $assetAttributeBuilder;
    }

    public function getMappedProperties(FamilyCode $familyCode): array
    {
        return array_keys($this->getFamilyMapping($familyCode));
    }

    public function mapAttribute(
        FamilyCode $familyCode,
        string $damAttributeProperty
    ): Attribute {
        $attributeCode = $this->getAttributeCodeFromFamilyMapping(
            $this->getFamilyMapping($familyCode),
            $damAttributeProperty
        );

        return $this->assetAttributeBuilder->build($familyCode, $attributeCode);
    }

    private function getMappingConfig(): array
    {
        if (!$this->mappingConfig) {
            $this->mappingConfig = $this->mappingConfigLoader->load();
        }

        return $this->mappingConfig;
    }

    private function getFamilyMapping(FamilyCode $familyCode): array
    {
        $mapping = $this->getMappingConfig();

        if (!isset($mapping[(string)$familyCode])) {
            throw new FamilyMappingNotFound(); // TODO Maybe we should not trigger an Exception here?!
        }

        return $mapping[(string)$familyCode];
    }

    private function getAttributeCodeFromFamilyMapping(
        array $familyMapping,
        string $damAttributeProperty
    ): AttributeCode {
        if (!isset($familyMapping[$damAttributeProperty])) {
            throw new AttributeMappingNotFound();
        }

        return new AttributeCode($familyMapping[$damAttributeProperty]);
    }
}
