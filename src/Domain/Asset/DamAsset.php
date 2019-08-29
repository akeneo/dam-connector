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

use AkeneoDAMConnector\Domain\AssetFamily;

/**
 * @author Romain Monceau <romain@akeneo.com>
 */
class DamAsset
{
    private $assetFamily;
    private $pimLocale;
    private $values;

    public function __construct(AssetFamily $assetFamily, string $pimLocale)
    {
        if (1 !== preg_match('/^[a-z]{2}_[A-Z]{2}$/', $pimLocale)) {
            throw new \Exception('Incorrect locale format!');
        }

        $this->assetFamily = $assetFamily;
        $this->pimLocale = $pimLocale;
        $this->values = [];
    }

    public function addValue(string $property, string $value): void
    {
        $this->values[(string) $property] = new DamAssetValue($property, $value);
    }

    /**
     * @return DamAssetValue[]
     */
    public function getValues(): array
    {
        return $this->values;
    }

    public function assetFamily(): AssetFamily
    {
        return $this->assetFamily;
    }
}
