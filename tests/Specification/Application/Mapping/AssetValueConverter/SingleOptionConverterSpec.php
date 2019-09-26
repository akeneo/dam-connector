<?php
declare(strict_types=1);

namespace Specification\AkeneoDAMConnector\Application\Mapping\AssetValueConverter;

use AkeneoDAMConnector\Application\Mapping\AssetValueConverter;
use AkeneoDAMConnector\Application\Mapping\AssetValueConverter\SingleOptionConverter;
use AkeneoDAMConnector\Domain\Asset\DamAssetValue;
use AkeneoDAMConnector\Tests\Specification\Builder\AssetAttributeBuilder;
use AkeneoDAMConnector\Tests\Specification\Builder\DamAssetBuilder;
use PhpSpec\ObjectBehavior;

class SingleOptionConverterSpec extends ObjectBehavior
{
    public function it_is_a_single_option_converter(): void
    {
        $this->shouldHaveType(SingleOptionConverter::class);
        $this->shouldImplement(AssetValueConverter::class);
    }

    public function it_provides_a_supported_type(): void
    {
        $this->getSupportedType()->shouldReturn('single_option');
    }

    public function it_converts_a_dam_asset_value_into_a_localized_pim_asset_value(): void
    {
        $damAsset = DamAssetBuilder::build('table', 'packshot', ['color' => 'temur'], 'en_US');
        $attribute = AssetAttributeBuilder::build('color', 'single_option', true);
        $damAssetValue = new DamAssetValue('color', 'temur');

        $pimValue = $this->convert($damAsset, $damAssetValue, $attribute);
        $pimValue->getAttribute()->shouldReturn($attribute);
        $pimValue->getData()->shouldReturn('temur');
        $pimValue->getLocale()->shouldReturn('en_US');
        $pimValue->getChannel()->shouldReturn(null);
    }

    public function it_converts_a_dam_asset_value_into_a_not_localized_pim_asset_value(): void
    {
        $damAsset = DamAssetBuilder::build('table', 'packshot', ['color' => 'temur']);
        $attribute = AssetAttributeBuilder::build('color', 'single_option', false);
        $damAssetValue = new DamAssetValue('color', 'temur');

        $pimValue = $this->convert($damAsset, $damAssetValue, $attribute);
        $pimValue->getAttribute()->shouldReturn($attribute);
        $pimValue->getData()->shouldReturn('temur');
        $pimValue->getLocale()->shouldReturn(null);
        $pimValue->getChannel()->shouldReturn(null);
    }
}
