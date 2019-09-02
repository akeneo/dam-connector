<?php

declare(strict_types=1);

/*
 * This file is part of the Akeneo PIM Enterprise Edition.
 *
 * (c) 2019 Akeneo SAS (http://www.akeneo.com)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AkeneoDAMConnector\Domain\Asset;

use AkeneoDAMConnector\Domain\AssetFamilyCode;
use AkeneoDAMConnector\Domain\Locale;
use AkeneoDAMConnector\Domain\ResourceType;

/**
 * @author Romain Monceau <romain@akeneo.com>
 */
class DamAsset
{
    /** @var DamAssetIdentifier */
    private $damAssetIdentifier;

    /** @var AssetFamilyCode */
    private $assetFamilyCode;

    /** @var Locale */
    private $locale;

    /** @var []DamAssetValue */
    private $values;

    /** @var ResourceType */
    private $resourceType;

    public function __construct(
        DamAssetIdentifier $damAssetIdentifier,
        AssetFamilyCode $assetFamilyCode,
        Locale $locale,
        ResourceType $resourceType
    ) {
        $this->damAssetIdentifier = $damAssetIdentifier;
        $this->assetFamilyCode = $assetFamilyCode;
        $this->locale = $locale;
        $this->resourceType = $resourceType;
        $this->values = [];
    }

    public function addValue(string $property, string $value): void
    {
        $this->values[(string) $property] = new DamAssetValue($property, $value);
    }

    public function getResourceType(): ResourceType
    {
        return $this->resourceType;
    }

    /**
     * @return DamAssetValue[]
     */
    public function getValues(): array
    {
        return $this->values;
    }

    public function assetFamilyCode(): AssetFamilyCode
    {
        return $this->assetFamilyCode;
    }

    public function locale(): Locale
    {
        return $this->locale;
    }

    public function damAssetIdentifier(): DamAssetIdentifier
    {
        return $this->damAssetIdentifier;
    }
}
