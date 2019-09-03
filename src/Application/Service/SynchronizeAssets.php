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
use AkeneoDAMConnector\Domain\AssetFamilyCode;
use AkeneoDAMConnector\Domain\Asset\PimAssetCollection;
use AkeneoDAMConnector\Infrastructure\Pim\UpdateAssetApi;

/**
 * @author Romain Monceau <romain@akeneo.com>
 */
class SynchronizeAssets
{
    private $fetchAssets;
    private $assetTransformer;
    private $assetApi;

    public function __construct(FetchAssets $fetchAssets, AssetTransformer $assetTransformer, UpdateAssetApi $assetApi)
    {
        $this->fetchAssets = $fetchAssets;
        $this->assetTransformer = $assetTransformer;
        $this->assetApi = $assetApi;
    }

    public function execute()
    {
        $lastFetchDate = new \DateTime('2019-08-12T15:38:00Z');

        // 1. Fetch PIM asset families
        $assetFamily = new AssetFamilyCode('packshot');

        // 2. Fetch assets by family from the DAM
        $damAssets = $this->fetchAssets->fetch($lastFetchDate, $assetFamily);

        $pimAssets = new PimAssetCollection();
        foreach ($damAssets as $damAsset) {
            // 3. Transform DAM Asset to PIM Asset filtering and mapping fields
            $pimAssets->addAsset($this->assetTransformer->damToPim($damAsset));
        }

        // 4. Push assets in the PIM
        $results = $this->assetApi->upsertList($assetFamily, $pimAssets);

        var_dump($results);
    }
}
