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

namespace AkeneoDAMConnector\Infrastructure\DAM\Cloudinary;

use AkeneoDAMConnector\Application\DamAdapter\FetchAssets;
use AkeneoDAMConnector\Domain\AssetFamily;
use AkeneoDAMConnector\Domain\Assets;

/**
 * @author Romain Monceau <romain@akeneo.com>
 */
class CloudinaryFetchAssets implements FetchAssets
{
    public function fetch(\DateTime $lastFetchDate, AssetFamily $assetFamily = null): Assets
    {
        // TODO: Implement fetch() method.
    }
}
