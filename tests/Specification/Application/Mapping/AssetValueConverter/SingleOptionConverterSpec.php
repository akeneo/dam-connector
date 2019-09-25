<?php
declare(strict_types=1);

namespace Specification\AkeneoDAMConnector\Application\Mapping\AssetValueConverter;

use AkeneoDAMConnector\Application\Mapping\AssetValueConverter;
use AkeneoDAMConnector\Application\Mapping\AssetValueConverter\SingleOptionConverter;
use AkeneoDAMConnector\Domain\Asset\DamAsset;
use AkeneoDAMConnector\Domain\Asset\DamAssetValue;
use AkeneoDAMConnector\Domain\AssetAttribute;
use AkeneoDAMConnector\Domain\Locale;
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

    public function it_converts_a_dam_asset_value_into_a_localized_pim_asset_value(
        DamAsset $damAsset,
        DamAssetValue $damAssetValue,
        AssetAttribute $attribute,
        Locale $locale
    ): void {
        $attribute->isLocalizable()->willReturn(true);
        $damAsset->locale()->willReturn($locale);
        $locale->__toString()->willReturn('en_US');

        $damAssetValue->value()->willReturn('blue');

        $pimValue = $this->convert($damAsset, $damAssetValue, $attribute);
        $pimValue->getAttribute()->shouldReturn($attribute);
        $pimValue->getData()->shouldReturn('blue');
        $pimValue->getLocale()->shouldReturn('en_US');
        $pimValue->getChannel()->shouldReturn(null);
    }

    public function it_converts_a_dam_asset_value_into_a_not_localized_pim_asset_value(
        DamAsset $damAsset,
        DamAssetValue $damAssetValue,
        AssetAttribute $attribute
    ): void {
        $attribute->isLocalizable()->willReturn(false);

        $damAssetValue->value()->willReturn('blue');

        $pimValue = $this->convert($damAsset, $damAssetValue, $attribute);
        $pimValue->getAttribute()->shouldReturn($attribute);
        $pimValue->getData()->shouldReturn('blue');
        $pimValue->getLocale()->shouldReturn(null);
        $pimValue->getChannel()->shouldReturn(null);
    }
}
