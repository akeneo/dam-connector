<?php

declare(strict_types=1);

namespace AkeneoDAMConnector\Application\Mapping;

use AkeneoDAMConnector\Application\ConfigLoader;
use AkeneoDAMConnector\Domain\AssetAttribute;
use AkeneoDAMConnector\Domain\AssetAttributeCode;
use AkeneoDAMConnector\Domain\AssetFamilyCode;
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

    public function getMappedProperties(AssetFamilyCode $familyCode): array
    {
        return array_keys($this->getFamilyMapping($familyCode));
    }

    public function mapAttribute(
        AssetFamilyCode $familyCode,
        string $damAttributeProperty
    ): AssetAttribute {
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

    private function getFamilyMapping(AssetFamilyCode $familyCode): array
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
    ): AssetAttributeCode {
        if (!isset($familyMapping[$damAttributeProperty])) {
            throw new AttributeMappingNotFound();
        }

        return new AssetAttributeCode($familyMapping[$damAttributeProperty]);
    }
}
