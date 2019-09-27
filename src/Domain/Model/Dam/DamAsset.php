<?php

declare(strict_types=1);

namespace AkeneoDAMConnector\Domain\Model\Dam;

use AkeneoDAMConnector\Domain\Model\FamilyCode;
use AkeneoDAMConnector\Domain\Model\Locale;

class DamAsset
{
    /** @var DamAssetIdentifier */
    private $damAssetIdentifier;

    /** @var FamilyCode */
    private $assetFamilyCode;

    /** @var Locale|null */
    private $locale;

    /** @var DamAssetValue[] */
    private $values;

    public function __construct(
        DamAssetIdentifier $damAssetIdentifier,
        FamilyCode $assetFamilyCode,
        ?Locale $locale
    ) {
        $this->damAssetIdentifier = $damAssetIdentifier;
        $this->assetFamilyCode = $assetFamilyCode;
        $this->locale = $locale;
        $this->values = [];
    }

    public function addValue(string $property, string $value): void
    {
        $this->values[$property] = new DamAssetValue($property, $value);
    }

    public function damAssetIdentifier(): DamAssetIdentifier
    {
        return $this->damAssetIdentifier;
    }

    public function assetFamilyCode(): FamilyCode
    {
        return $this->assetFamilyCode;
    }

    public function locale(): Locale
    {
        return $this->locale;
    }

    /**
     * @return DamAssetValue[]
     */
    public function getValues(): array
    {
        return $this->values;
    }
}
