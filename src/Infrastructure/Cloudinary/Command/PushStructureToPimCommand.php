<?php

declare(strict_types=1);

namespace AkeneoDAMConnector\Infrastructure\Cloudinary\Command;

use AkeneoDAMConnector\Infrastructure\Cloudinary\Search;
use AkeneoDAMConnector\Infrastructure\Pim\AssetFamilyApi;
use AkeneoDAMConnector\Infrastructure\Pim\AssetFamilyAttributeApi;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PushStructureToPimCommand extends Command
{
    /** @var AssetFamilyApi */
    private $assetFamilyApi;

    /** @var Search */
    private $cloudinarySearch;

    /** @var AssetFamilyAttributeApi */
    private $assetFamilyAttributeApi;

    protected function configure()
    {
        $this
            ->setName('cloudinary-connector:structure:push')
            ->setDescription('Pushes structure from Cloudinary DAM to Akeneo PIM Asset Manager.');
    }

    public function __construct(
        Search $cloudinarySearch,
        AssetFamilyApi $assetFamilyApi,
        AssetFamilyAttributeApi $assetFamilyAttributeApi
    ) {
        $this->assetFamilyApi = $assetFamilyApi;
        $this->cloudinarySearch = $cloudinarySearch;
        $this->assetFamilyAttributeApi = $assetFamilyAttributeApi;

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $response = $this->cloudinarySearch->search(
            'tags:akeneo AND (folder="illustration pictures" OR folder="illustration videos")',
            ['tags', 'context']
        );

        $assets = $response['resources'];
        $familiesToUpsert = [
            'illustration_picture' => [],
            'illustration_video' => [],
            'other' => []
        ];
        foreach ($assets as $index => $asset) {
            $type = $asset['resource_type'];
            $name = $asset['filename'];
            $folder = $asset['folder'];
            $attributes = $asset['context'];
            $assetFamilyCode = $this->guessFamily($type, $folder);
            $familiesToUpsert[$assetFamilyCode] = array_merge(
                $familiesToUpsert[$assetFamilyCode],
                array_diff(array_keys($attributes), $familiesToUpsert[$assetFamilyCode])
            );
        }
        foreach ($familiesToUpsert as $familyCode => $familyAttributes) {
            $this->assetFamilyApi->upsertFamily($familyCode, ['code' => $familyCode]);
            switch ($familyCode) {
                case 'illustration_picture':
                    $this->assetFamilyAttributeApi->upsertFamilyAttribute($familyCode, 'picture', [
                        'code' => 'picture',
                        'type' => 'media_link',
                        'media_type' => 'image',
                        'value_per_locale' => false,
                        'value_per_channel' => false,
                        'is_required_for_completeness' => false,
                        'prefix' => 'https://res.cloudinary.com/akeneo/',
                        'suffix' => null,
                    ]);
                    break;
                case 'illustration_video':
                    $this->assetFamilyAttributeApi->upsertFamilyAttribute($familyCode, 'video', [
                        'code' => 'video',
                        'labels' => [],
                        'type' => 'media_link',
                        'value_per_locale' => false,
                        'value_per_channel' => false,
                        'is_required_for_completeness' => false,
                        'prefix' => 'https://res.cloudinary.com/akeneo/',
                        'suffix' => null,
                        'media_type' => 'other'
                    ]);
                    break;
            }
            foreach ($familyAttributes as $attribute) {
                $this->assetFamilyAttributeApi->upsertFamilyAttribute($familyCode, $attribute, [
                    'code' => $attribute,
                    'type' => 'text',
                    'value_per_locale' => false,
                    'value_per_channel' => false,
                    'is_required_for_completeness' => false,
                    'is_textarea' => false,
                    'is_rich_text_editor' => false,
                    'validation_regexp' => null
                ]);
            }
        }
    }


    private function guessFamily(string $resourceType, string $folder): string
    {
        if ('image' === $resourceType && 'illustration pictures' === $folder) {
            return 'illustration_picture';
        }
        if ('video' === $resourceType && 'illustration videos' === $folder) {
            return 'illustration_video';
        }
        return 'other';
    }
}
