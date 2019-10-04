<?php


declare(strict_types=1);

namespace AkeneoDAMConnector\Application\Mapping;

use AkeneoDAMConnector\Application\ConfigLoader;
use AkeneoDAMConnector\Domain\Model\FamilyCode;
use AkeneoDAMConnector\Domain\Model\Pim\Attribute;
use AkeneoDAMConnector\Domain\Model\Pim\AttributeCode;

class AssetAttributeBuilder
{
    private $structureConfigLoader;
    private $structureConfig;

    public function __construct(ConfigLoader $structureConfigLoader)
    {
        $this->structureConfigLoader = $structureConfigLoader;
    }

    public function build(FamilyCode $familyCode, AttributeCode $attributeCode): Attribute
    {
        $attributeType = $this->getAttributeType($familyCode, $attributeCode);

        return new Attribute($attributeCode, $attributeType, false);
    }

    private function getStructureConfig(): array
    {
        if (!$this->structureConfig) {
            $this->structureConfig = $this->structureConfigLoader->load();
        }

        return $this->structureConfig;
    }

    private function getAttributeType(FamilyCode $familyCode, AttributeCode $attributeCode): string
    {
        foreach ($this->getStructureConfig()[(string)$familyCode]['attributes'] as $attribute) {
            if ($attribute['code'] === (string)$attributeCode) {
                return $attribute['type'];
            }
        }

        throw new \RuntimeException();
    }
}



