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
    /** @var FetchAssets */
    private $fetchAssets;

    /** @var AssetTransformer */
    private $assetTransformer;

    /** @var UpdateAssetApi */
    private $assetApi;

    /** @var SynchronizeAttributeOptions */
    private $synchronizeAttributeOptions;

    public function __construct(
        FetchAssets $fetchAssets,
        AssetTransformer $assetTransformer,
        UpdateAssetApi $assetApi,
        SynchronizeAttributeOptions $synchronizeAttributeOptions
    ) {
        $this->fetchAssets = $fetchAssets;
        $this->assetTransformer = $assetTransformer;
        $this->assetApi = $assetApi;
        $this->synchronizeAttributeOptions = $synchronizeAttributeOptions;
    }

    public function execute()
    {
        $lastFetchDate = new \DateTime('2019-08-12T15:38:00Z');

        // 1. Fetch PIM asset families
        $assetFamily = new AssetFamilyCode('packshot');

        // 2. Fetch assets by family from the DAM
        $damAssets = $this->fetchAssets->fetch($lastFetchDate, $assetFamily);

        $options = [];
        $pimAssets = new PimAssetCollection();
        foreach ($damAssets as $damAsset) {
            // 3. Transform DAM Asset to PIM Asset filtering and mapping fields
            $pimAsset = $this->assetTransformer->damToPim($damAsset);
            $options = array_merge($options, $pimAsset->getValuesWithOptions());

            $pimAssets->addAsset($pimAsset);
        }

        // 4. Synchronize attribute options between assets to upsert and PIM
        $this->synchronizeAttributeOptions->execute($assetFamily, $options);

        // 5. Push assets in the PIM
        $results = $this->assetApi->upsertList($assetFamily, $pimAssets);
    }
}
