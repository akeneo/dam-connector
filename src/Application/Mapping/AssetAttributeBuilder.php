<?php


declare(strict_types=1);

namespace AkeneoDAMConnector\Application\Mapping;

use AkeneoDAMConnector\Application\ConfigLoader;
use AkeneoDAMConnector\Domain\AssetAttribute;
use AkeneoDAMConnector\Domain\AssetAttributeCode;
use AkeneoDAMConnector\Domain\AssetFamilyCode;

class AssetAttributeBuilder
{
    private $structureConfigLoader;
    private $structureConfig;

    public function __construct(ConfigLoader $structureConfigLoader)
    {
        $this->structureConfigLoader = $structureConfigLoader;
    }

    public function build(AssetFamilyCode $familyCode, AssetAttributeCode $attributeCode): AssetAttribute
    {
        $attributeType = $this->getAttributeType($familyCode, $attributeCode);

        return new AssetAttribute($attributeCode, $attributeType, false);
    }

    private function getStructureConfig(): array
    {
        if (!$this->structureConfig) {
            $this->structureConfig = $this->structureConfigLoader->load();
        }

        return $this->structureConfig;
    }

    private function getAttributeType(AssetFamilyCode $familyCode, AssetAttributeCode $attributeCode): string
    {
        foreach ($this->getStructureConfig()[(string)$familyCode]['attributes'] as $attribute) {
            if ($attribute['code'] === (string)$attributeCode) {
                return $attribute['type'];
            }
        }

        throw new \RuntimeException();
    }
}



