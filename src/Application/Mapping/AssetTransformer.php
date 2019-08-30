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

namespace AkeneoDAMConnector\Application\Mapping;

use AkeneoDAMConnector\Domain\Asset\DamAsset;
use AkeneoDAMConnector\Domain\AssetFamily;
use AkeneoDAMConnector\Domain\Pim\Asset;
use AkeneoDAMConnector\Domain\Pim\AssetAttribute;
use AkeneoDAMConnector\Domain\Pim\AssetValue;

/**
 * @author Romain Monceau <romain@akeneo.com>
 */
class AssetTransformer
{
    private $mapping = [];

    /**
     * @param AssetFamily $assetFamily
     *
     * TODO: To implement
     */
    public function loadAssetFamilyMapping(AssetFamily $assetFamily): void
    {
        $this->mapping = [
            'pdf_notices' => [
                'filetype' => new AssetAttribute('filetype', 'text', false),
                'filesize' => new AssetAttribute('size', 'text', false),
                'author'   => new AssetAttribute('photograph', 'single_option', false),
                'original' => new AssetAttribute('preview', 'media_link', false),
            ]
        ];
    }

    private function getAssetFamilyMapping(AssetFamily $assetFamily): array
    {
        if (!isset($this->mapping[$assetFamily->getCode()])) {
            $this->loadAssetFamilyMapping($assetFamily);
        }

        return $this->mapping[$assetFamily->getCode()];
    }

    public function damToPim(DamAsset $damAsset): Asset
    {
        $assetFamilyMapping = $this->getAssetFamilyMapping($damAsset->assetFamily());

        $assetValues = [];
        foreach ($damAsset->getValues() as $damAssetValue) {
            // 1. Filter values we don't want
            if (!isset($this->mapping[$damAssetValue->property()])) {
                continue;
            }

            // 2. Map DAM property to PIM attribute
            $assetAttribute = $assetFamilyMapping[$damAssetValue->property()];

            // 3. Transform DAM Asset Value into a PIM Asset Value
            switch ($assetAttribute->getType()) {
                case 'media_link':

                    break;
                case 'single_option':

                    break;
                case 'multiple_option':

                    break;
                default:
                    $assetValues[] = new AssetValue($assetAttribute, $damAssetValue->value(), $damAsset->locale(), null);
                    break;
            }
        }

        return new Asset((string) $damAsset->damAssetIdentifier(), $assetValues);
    }
}
