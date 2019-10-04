<?php
declare(strict_types=1);

namespace AkeneoDAMConnector\Tests\Specification\Builder;

use AkeneoDAMConnector\Domain\Model\Dam\DamAsset;
use AkeneoDAMConnector\Domain\Model\Dam\DamAssetIdentifier;
use AkeneoDAMConnector\Domain\Model\FamilyCode;
use AkeneoDAMConnector\Domain\Model\Locale;

class DamAssetBuilder
{
    public static function build(
        string $identifier,
        string $assetFamilyCode,
        array $values = [],
        ?string $locale = null
    ): DamAsset {
        $identifier = new DamAssetIdentifier($identifier);
        $assetFamilyCode = new FamilyCode($assetFamilyCode);
        $locale = null !== $locale ? new Locale($locale) : null;

        $damAsset = new DamAsset($identifier, $assetFamilyCode, $locale);

        foreach ($values as $property => $value) {
            $damAsset->addValue($property, $value);
        }

        return $damAsset;
    }
}
