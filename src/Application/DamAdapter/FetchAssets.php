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

namespace AkeneoDAMConnector\Application\DamAdapter;

use AkeneoDAMConnector\Domain\Asset\DamAssetCollection;
use AkeneoDAMConnector\Domain\AssetFamilyCode;

/**
 * @author Romain Monceau <romain@akeneo.com>
 */
interface FetchAssets
{
    /**
     * Fetches assets on DAM third party system depending on the last fetch date
     *
     * TODO: AssetFamily is optional because not implemented yet
     *
     * @param \DateTime $lastFetchDate
     * @param AssetFamilyCode $assetFamilyCode
     *
     * @return DamAssetCollection
     */
    public function fetch(\DateTime $lastFetchDate, AssetFamilyCode $assetFamilyCode): DamAssetCollection;
}
