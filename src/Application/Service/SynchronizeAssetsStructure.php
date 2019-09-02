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

use Akeneo\Pim\ApiClient\Exception\HttpException;
use Akeneo\Pim\ApiClient\Exception\UnprocessableEntityHttpException;
use AkeneoDAMConnector\Application\StructureConfig\StructureConfigLoader;
use AkeneoDAMConnector\Infrastructure\Pim\AssetAttributeApi;
use AkeneoDAMConnector\Infrastructure\Pim\AssetFamilyApi;

/**
 * @author Romain Monceau <romain@akeneo.com>
 */
class SynchronizeAssetsStructure
{
    private $loader;
    private $assetFamilyApi;
    private $assetAttributeApi;

    public function __construct(StructureConfigLoader $loader, AssetFamilyApi $assetFamilyApi, AssetAttributeApi $assetAttributeApi)
    {
        $this->loader = $loader;
        $this->assetFamilyApi = $assetFamilyApi;
        $this->assetAttributeApi = $assetAttributeApi;
    }

    public function execute()
    {
        $structureConfig = $this->loader->load();
        foreach ($structureConfig as $assetFamilyCode => $assetFamilyConfig) {
            $assetFamilyData = [
                'code' => $assetFamilyCode,
            ];

            // 1. Create family
            $this->assetFamilyApi->upsertFamily($assetFamilyCode, $assetFamilyData);

            // 2. Adds attribute to family
            foreach ($assetFamilyConfig['attributes'] as $assetAttributeConfig) {
                try {
                    $this->assetAttributeApi->upsert($assetFamilyCode, $assetAttributeConfig['code'], $assetAttributeConfig);
                } catch (UnprocessableEntityHttpException $e) {
                    foreach ($e->getResponseErrors() as $error) {
                        if ($error['message'] !== 'There should be updates to perform on the attribute. None found.') {
                            throw $e;
                        }
                    }
                }
            }

            // 3. Adds product rule asset assignation to the family
            $assetFamilyData['product_link_rules'] = [$assetFamilyConfig['product_link_rules']];
            $this->assetFamilyApi->upsertFamily($assetFamilyCode, $assetFamilyData);
        }
    }
}
