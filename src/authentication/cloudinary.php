<?php

declare(strict_types=1);

require_once('vendor/autoload.php');

use authentication\PimApi;

Cloudinary::config(array(
    'cloud_name' => 'akeneo',
    'api_key' => '',
    'api_secret' => '',
    'secure' => true
));

$pimApi = new PimApi();
//
//$pimApi->upsertFamilyAttribute('illustration_picture', 'test_encore123', [
//    'code' => 'test_encore123',
//    'type' => 'text',
//    'value_per_locale' => false,
//    'value_per_channel' => false,
//    'is_required_for_completeness' => false,
//    'is_textarea' => false,
//    'is_rich_text_editor' => false,
//    'validation_regexp' => null
//]);
//
//die();
$search = new Cloudinary\Search();
$response = $search
    ->expression('tags:akeneo AND (folder="illustration pictures" OR folder="illustration videos")')
    ->with_field('tags')
    ->with_field('context')
    ->execute();


$totalCount = $response['total_count'];
$time = $response['time'];
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
    $assetFamilyCode = guessFamily($type, $folder);

    $familiesToUpsert[$assetFamilyCode] = array_merge(
        $familiesToUpsert[$assetFamilyCode],
        array_diff(array_keys($attributes), $familiesToUpsert[$assetFamilyCode])
    );

}

foreach ($familiesToUpsert as $familyCode => $familyAttributes) {

    $pimApi->upsertFamily($familyCode, ['code' => $familyCode]);

    switch ($familyCode) {
        case 'illustration_picture':
            $pimApi->upsertFamilyAttribute($familyCode, 'image', [
                'code' => 'image',
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
            $pimApi->upsertFamilyAttribute($familyCode, 'video', [
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
        $pimApi->upsertFamilyAttribute($familyCode, $attribute, [
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

function guessFamily(string $resourceType, string $folder): string
{
    if ('image' === $resourceType && 'illustration pictures' === $folder) {
        return 'illustration_picture';
    }
    if ('video' === $resourceType && 'illustration videos' === $folder) {
        return 'illustration_video';
    }

    return 'other';
}

var_dump($familiesToUpsert);
die();

$assetFamilyAttribute = [
    'code' => 'media_preview',
    'labels' => [
        'en_US' => 'Media Preview'
    ],
    'type' => 'media_link',
    "value_per_locale" => false,
    "value_per_channel" => false,
    "is_required_for_completeness" => false,
    "prefix" => "dam.com/my_assets/",
    "suffix" => null,
    "media_type" => "image"
];