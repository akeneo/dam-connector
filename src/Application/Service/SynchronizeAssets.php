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
use AkeneoDAMConnector\Application\Mapping\AssetTransformer;
use AkeneoDAMConnector\Domain\AssetFamily;
use AkeneoDAMConnector\Domain\Asset\DamAsset;
use AkeneoDAMConnector\Infrastructure\Pim\ClientBuilder;

/**
 * @author Romain Monceau <romain@akeneo.com>
 */
class SynchronizeAssets
{
    private $fetchAssets;
    private $clientBuilder;
    private $assetTransformer;

    public function __construct(FetchAssets $fetchAssets, ClientBuilder $clientBuilder, AssetTransformer $assetTransformer)
    {
        $this->fetchAssets = $fetchAssets;
        $this->clientBuilder = $clientBuilder;
        $this->assetTransformer = $assetTransformer;
    }

    public function execute()
    {
        $lastFetchDate = new \DateTime('2019-08-12T15:38:00Z');

        // 1. Fetch PIM asset families

        // 2. Fetch assets by family from the DAM
        $assets = $this->fetchAssets->fetch($lastFetchDate, new AssetFamily('illustration pictures'));
        foreach ($damAssets as $damAsset) {
            // 3. Transform DAM Asset to PIM Asset filtering and mapping fields
            $pimAsset = $this->assetTransformer->damToPim($damAsset);
        }

        // 4. Push assets in the PIM
    }
}
