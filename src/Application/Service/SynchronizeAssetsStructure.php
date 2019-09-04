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

use Akeneo\Pim\ApiClient\Exception\UnprocessableEntityHttpException;
use AkeneoDAMConnector\Application\ConfigLoader;
use AkeneoDAMConnector\Application\PimAdapter\UpdateAssetStructure;

/**
 * @author Romain Monceau <romain@akeneo.com>
 */
class SynchronizeAssetsStructure
{
    private $structureConfigLoader;
    private $updateAssetStructure;

    public function __construct(ConfigLoader $structureConfigLoader, UpdateAssetStructure $updateAssetStructure)
    {
        $this->structureConfigLoader = $structureConfigLoader;
        $this->updateAssetStructure = $updateAssetStructure;
    }

    public function execute()
    {
        $structureConfig = $this->structureConfigLoader->load();
        foreach ($structureConfig as $assetFamilyCode => $assetFamilyConfig) {
            echo "Creating family: ". $assetFamilyCode ."\n";
            $assetFamilyData = [
                'code' => $assetFamilyCode,
            ];

            // 1. Create family
            $this->updateAssetStructure->upsertFamily($assetFamilyCode, $assetFamilyData);

            // 2. Adds attribute to family
            foreach ($assetFamilyConfig['attributes'] as $assetAttributeConfig) {
                // TODO: Try/catch done because an error http code is sent when no change on attribute
                try {
                    $this->updateAssetStructure->upsertAttribute($assetFamilyCode, $assetAttributeConfig['code'], $assetAttributeConfig);
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
            $this->updateAssetStructure->upsertFamily($assetFamilyCode, $assetFamilyData);
        }
    }
}
