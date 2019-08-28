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

namespace AkeneoDAMConnector\Application\Service;

use AkeneoDAMConnector\Application\DamAdapter\GetFilteredAssets;
use AkeneoDAMConnector\Infrastructure\Pim\ClientBuilder;

/**
 * @author Romain Monceau <romain@akeneo.com>
 */
class SynchronizeAssets
{
    private $getFilteredAssets;
    private $clientBuilder;

    public function __construct(GetFilteredAssets $getFilteredAssets, ClientBuilder $clientBuilder)
    {
        $this->getFilteredAssets = $getFilteredAssets;
        $this->clientBuilder = $clientBuilder;
    }

    public function execute()
    {
        $lastFetchDate = new \DateTime('2019-08-12T15:38:00Z');

        $assets = $this->getFilteredAssets->fetch($lastFetchDate, null);
        foreach ($assets as $asset) {
            // TODO: Algo to prepare conversion to Akeneo PIM Asset
            
        }
    }
}
