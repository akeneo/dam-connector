<?php
declare(strict_types=1);

namespace Specification\AkeneoDAMConnector\Application\Mapping;

use AkeneoDAMConnector\Application\Mapping\AssetConverter;
use AkeneoDAMConnector\Application\Mapping\AssetMapper;
use AkeneoDAMConnector\Domain\Asset\DamAssetValue;
use AkeneoDAMConnector\Domain\Asset\PimAssetValue;
use AkeneoDAMConnector\Domain\AssetFamilyCode;
use AkeneoDAMConnector\Tests\Specification\Builder\AssetAttributeBuilder;
use AkeneoDAMConnector\Tests\Specification\Builder\DamAssetBuilder;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class AssetTransformerSpec extends ObjectBehavior
{
    public function let(
        AssetMapper $assetMapper,
        AssetConverter $assetConverter
    ): void {
        $this->beConstructedWith($assetMapper, $assetConverter);
    }

    public function it_transforms_a_dam_asset_to_pim_asset($assetMapper, $assetConverter): void
    {
        $mappedProperties = ['sku', 'colors', 'url'];

        $familyCode = new AssetFamilyCode('packshot');
        $damAsset = DamAssetBuilder::build(
            'dam_identifier',
            'packshot',
            [
                'sku' => '123456',
                'colors' => 'blue, green',
                'designed_by' => 'stark',
            ]
        );
        $skuValue = new DamAssetValue('sku', '123456');
        $colorsValue = new DamAssetValue('colors', 'blue, green');
        $designedByValue = new DamAssetValue('designed_by', 'stark');

        $skuAttribute = AssetAttributeBuilder::build('sku', 'text');
        $colorsAttribute = AssetAttributeBuilder::build('all_colors', 'multiple_options');
        $skuPimValue = new PimAssetValue($skuAttribute, '123456');
        $colorsPimValue = new PimAssetValue($colorsAttribute, ['blue', 'green']);

        $assetMapper->getMappedProperties($familyCode)->willReturn($mappedProperties);
        $assetMapper->mapAttribute($familyCode, 'sku')->willReturn($skuAttribute);
        $assetMapper->mapAttribute($familyCode, 'colors')->willReturn($colorsAttribute);
        $assetMapper->mapAttribute($familyCode, 'designed_by')->shouldNotBeCalled();

        $assetConverter->convert($damAsset, $skuValue, $skuAttribute)->shouldBeCalled()->willReturn($skuPimValue);
        $assetConverter->convert($damAsset, $designedByValue, Argument::any())->shouldNotBeCalled();
        $assetConverter
            ->convert($damAsset, $colorsValue, $colorsAttribute)
            ->shouldBeCalled()
            ->willReturn($colorsPimValue);

        $pimAsset = $this->damToPim($damAsset);
        $pimAsset->getCode()->shouldReturn('dam_identifier');
        $pimAsset->normalize()->shouldReturn([
            'code' => 'dam_identifier',
            'values' => [
                'sku' => [[
                    'locale' => null,
                    'channel' => null,
                    'data' => '123456',
                ]],
                'all_colors' => [[
                    'locale' => null,
                    'channel' => null,
                    'data' => ['blue', 'green'],
                ]],
            ]
        ]);
    }
}
