<?php
declare(strict_types=1);

namespace Specification\AkeneoDAMConnector\Application\Mapping;

use AkeneoDAMConnector\Application\Mapping\AssetConverter;
use AkeneoDAMConnector\Application\Mapping\AssetMapper;
use AkeneoDAMConnector\Domain\Asset\DamAsset;
use AkeneoDAMConnector\Domain\Asset\DamAssetIdentifier;
use AkeneoDAMConnector\Domain\Asset\DamAssetValue;
use AkeneoDAMConnector\Domain\Asset\PimAssetValue;
use AkeneoDAMConnector\Domain\AssetAttribute;
use AkeneoDAMConnector\Domain\AssetFamilyCode;
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

    public function it_transforms_a_dam_asset_to_pim_asset(
        $assetMapper,
        $assetConverter,
        DamAsset $damAsset,
        AssetFamilyCode $familyCode,
        DamAssetValue $skuValue,
        DamAssetValue $colorsValue,
        DamAssetValue $designedByValue,
        DamAssetIdentifier $damIdentifier,
        AssetAttribute $skuAttribute,
        AssetAttribute $colorsAttribute,
        PimAssetValue $skuPimValue,
        PimAssetValue $colorsPimValue
    ): void {
        $mappedProperties = ['sku', 'colors', 'url'];
        $damValues = [
            'sku' => $skuValue,
            'colors' => $colorsValue,
            'designed_by' => $designedByValue,
        ];
        $damAsset->assetFamilyCode()->willReturn($familyCode);
        $damAsset->getValues()->willReturn($damValues);
        $damAsset->damAssetIdentifier()->willReturn($damIdentifier);

        $damIdentifier->__toString()->willReturn('dam_identifier');

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
    }
}
