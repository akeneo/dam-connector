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

namespace AkeneoDAMConnector\Application\PimAdapter;

use AkeneoDAMConnector\Domain\AssetFamily;
use AkeneoDAMConnector\Domain\Pim\AssetCollection;

/**
 * @author Romain Monceau <romain@akeneo.com>
 */
interface UpdateAsset
{
    public function upsertList(AssetFamily $assetFamily, AssetCollection $assets): void;
}
