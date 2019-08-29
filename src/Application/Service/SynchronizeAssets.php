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

use AkeneoDAMConnector\Application\DamAdapter\FetchAssets;
use AkeneoDAMConnector\Domain\AssetFamily;
use AkeneoDAMConnector\Infrastructure\Pim\ClientBuilder;

/**
 * @author Romain Monceau <romain@akeneo.com>
 */
class SynchronizeAssets
{
    private $fetchAssets;
    private $clientBuilder;

    public function __construct(FetchAssets $fetchAssets, ClientBuilder $clientBuilder)
    {
        $this->fetchAssets = $fetchAssets;
        $this->clientBuilder = $clientBuilder;
    }

    public function execute()
    {
        $lastFetchDate = new \DateTime('2019-08-12T15:38:00Z');

        $assets = $this->fetchAssets->fetch($lastFetchDate, new AssetFamily('illustration pictures'));
        foreach ($assets as $asset) {
            // TODO: Algo to prepare conversion to Akeneo PIM Asset
        }
    }
}
