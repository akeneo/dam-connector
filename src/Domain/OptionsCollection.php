<?php

declare(strict_types=1);

namespace AkeneoDAMConnector\Domain;

/**
 * @author Willy Mesnage <willy.mesnage@akeneo.com>
 */
class OptionsCollection
{
    /** @var array */
    private $collection;

    public function __construct()
    {
        $this->collection = [];
    }

    public function addOptions(Options $options): void
    {
        $attributeCode = (string) $options->getAttributeCode();
        $familyCode = (string) $options->getFamilyCode();

        $hasIndex = $this->collection[$familyCode][$attributeCode] ?? false;

        if (false === $hasIndex) {
            $this->collection[$familyCode][$attributeCode] = $options;
        } else {
            $this->collection[$familyCode][$attributeCode]->merge($options);
        }
    }

    public function getOptionsByFamily(AssetFamilyCode $familyCode): array
    {
        return $this->collection[(string) $familyCode] ?? [];
    }

    public function getOptionsByAttribute(AssetFamilyCode $familyCode, AssetAttributeCode $attributeCode): ?Options
    {
        return $this->collection[(string) $familyCode][(string) $attributeCode] ?? null;
    }

    public function getOptions(): array
    {
        return $this->collection;
    }

    public function count(): int
    {
        $count = 0;
        foreach ($this->collection as $families) {
            foreach ($families as $options) {
                $count += $options->count();
            }
        }

        return $count;
    }

    public function removeFamily(AssetFamilyCode $familyCode): void
    {
        if (!array_key_exists((string) $familyCode, $this->collection)) {
            return;
        }

        unset($this->collection[(string) $familyCode]);
    }
}
