<?php

declare(strict_types=1);

namespace AkeneoDAMConnector\Infrastructure\DAM\Bynder\Command;

use AkeneoDAMConnector\Infrastructure\DAM\Bynder\ClientBuilder;
use AkeneoDAMConnector\Infrastructure\Pim\AssetFamilyApi;
use AkeneoDAMConnector\Infrastructure\Pim\AssetAttributeApi;
use Bynder\Api\Impl\BynderApi;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PushStructureToPimCommand extends Command
{
    /** @var AssetFamilyApi */
    private $assetFamilyApi;

    /** @var AssetAttributeApi */
    private $assetAttributeApi;

    /** @var BynderApi */
    private $bynderClient;

    protected function configure()
    {
        $this
            ->setName('bynder-connector:structure:push')
            ->setDescription('Pushes structure from Bynder DAM to Akeneo PIM Asset Manager.');
    }

    public function __construct(
        ClientBuilder $clientBuilder,
        AssetFamilyApi $assetFamilyApi,
        AssetAttributeApi $assetAttributeApi
    ) {
        $this->bynderClient = $clientBuilder->getClient();
        $this->assetFamilyApi = $assetFamilyApi;
        $this->assetAttributeApi = $assetAttributeApi;

        parent::__construct();
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $metaPropertyForFamiliesId = '753C2082-F36F-4AD3-9CBD2A238FDFC761';
        $metaPropertyForFamilies = $this->bynderClient->getAssetBankManager()->getMetaproperty($metaPropertyForFamiliesId)->wait();
        $damAssetFamilies = $metaPropertyForFamilies['options'];

        foreach ($damAssetFamilies as $damAssetFamily) {
            $lastFetchDate = '2019-08-12T15:38:00Z';
            echo PHP_EOL . '----------------------------------------------------' . PHP_EOL;
            echo sprintf("Analyzing family '%s' (%s)\n", $damAssetFamily['label'], $damAssetFamily['id']);
            $mediaFilter = [
                'propertyOptionId' => $damAssetFamily['id'],
                'dateModified' => $lastFetchDate,
            ];
            $mediaList = $this->bynderClient->getAssetBankManager()->getMediaList($mediaFilter)->wait();
            echo sprintf("%d medias have been found for family '%s'\n\n", count($mediaList), $damAssetFamily['label']);
            if (count($mediaList) === 0) {
                continue;
            }
            // 4. Synchronize Asset Family (structure)
            // 4.1. List properties that we want to sync
            // 4.1.1. List DAM Asset properties
            $damAssetProperties = [
                'id',          // UUID
                'name',        // string
                'description', // text
                'orientation', // string
                'copyright',   // string
                'watermarked', // bool
            ];
            // 4.1.2. List DAM Asset metaproperties
            $damAssetMetaProperties = [
                'property_extension',
                'property_assettype'
            ];
            // 4.1.3. List DAM Asset links to send
            $damAssetLinks = [
                'original',
                'thumbnails'
            ];
            // 4.1.4. Filter properties to send to the PIM
            $propertyKeysToSync = array_merge($damAssetProperties, $damAssetMetaProperties, $damAssetLinks);
            echo sprintf("%s properties have been selected to be synchronized with Akeneo PIM attributes\n", count($propertyKeysToSync));
            foreach ($propertyKeysToSync as $propertyKeyToSync) {
                echo sprintf("- %s\n", $propertyKeyToSync);
            }
            echo "\n";
            // 4.2. Upsert asset family
            // TODO It may be done in a dedicated script that synchronize Assets structure
            $damAssetFamilyCode = str_replace('-', '_', $damAssetFamily['name']);

            $this->assetFamilyApi->upsertFamily($damAssetFamilyCode, ['code' => $damAssetFamilyCode]);
            // 4.2. Upsert each property as a PIM attribute of the Asset Family
            // TODO It may be done in a dedicated script that synchronize Assets structure
            foreach (array_merge($damAssetProperties, $damAssetMetaProperties) as $pimAssetAttributeCode) {
                $pimAttributeData = [
                    'code' => $pimAssetAttributeCode,
                    'type' => 'text',
                    'max_characters' => 100,
                ];
                $this->assetAttributeApi->upsert(
                    $damAssetFamilyCode,
                    $pimAssetAttributeCode,
                    $pimAttributeData
                );
                echo sprintf("Attribute '%s' created\n", $pimAssetAttributeCode);
            }
            foreach ($damAssetLinks as $pimMediaLinkCode) {
                $pimAttributeData = [
                    'code' => $pimMediaLinkCode,
                    'type' => 'media_link',
                    'media_type' => 'image',
                ];
                $this->assetAttributeApi->upsert(
                    $damAssetFamilyCode,
                    $pimMediaLinkCode,
                    $pimAttributeData
                );
                echo sprintf("Attribute media link '%s' created\n", $pimMediaLinkCode);
            }
        }
    }
}
