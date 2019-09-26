<?php
declare(strict_types=1);

namespace AkeneoDAMConnector\Tests\Specification\Builder;

use AkeneoDAMConnector\Domain\Asset\DamAsset;
use AkeneoDAMConnector\Domain\Asset\DamAssetIdentifier;
use AkeneoDAMConnector\Domain\AssetFamilyCode;
use AkeneoDAMConnector\Domain\Locale;

class DamAssetBuilder
{
    public static function build(
        string $identifier,
        string $assetFamilyCode,
        array $values = [],
        ?string $locale = null
    ): DamAsset {
        $identifier = new DamAssetIdentifier($identifier);
        $assetFamilyCode = new AssetFamilyCode($assetFamilyCode);
        $locale = null !== $locale ? new Locale($locale) : null;

        $damAsset = new DamAsset($identifier, $assetFamilyCode, $locale);

        foreach ($values as $property => $value) {
            $damAsset->addValue($property, $value);
        }

        return $damAsset;
    }
}
