<?php
declare(strict_types=1);

namespace Specification\AkeneoDAMConnector\Application\Mapping\AssetValueConverter;

use AkeneoDAMConnector\Application\Mapping\AssetValueConverter;
use AkeneoDAMConnector\Application\Mapping\AssetValueConverter\MultiOptionConverter;
use AkeneoDAMConnector\Domain\Asset\DamAssetValue;
use AkeneoDAMConnector\Tests\Specification\Builder\AssetAttributeBuilder;
use AkeneoDAMConnector\Tests\Specification\Builder\DamAssetBuilder;
use PhpSpec\ObjectBehavior;

class MultiOptionConverterSpec extends ObjectBehavior
{
    public function it_is_a_multi_option_converter(): void
    {
        $this->shouldHaveType(MultiOptionConverter::class);
        $this->shouldImplement(AssetValueConverter::class);
    }

    public function it_provides_a_supported_type(): void
    {
        $this->getSupportedType()->shouldReturn('multiple_options');
    }

    public function it_converts_a_dam_asset_value_into_a_localized_pim_asset_value(): void
    {
        $damAsset = DamAssetBuilder::build('table', 'packshot', ['colors' => 'simic, blue, green'], 'en_US');
        $attribute = AssetAttributeBuilder::build('colors', 'multiple_options', true);
        $damAssetValue = new DamAssetValue('colors', 'simic, blue, green');

        $pimValue = $this->convert($damAsset, $damAssetValue, $attribute);
        $pimValue->getAttribute()->shouldReturn($attribute);
        $pimValue->getData()->shouldReturn(['simic', 'blue', 'green']);
        $pimValue->getLocale()->shouldReturn('en_US');
        $pimValue->getChannel()->shouldReturn(null);
    }

    public function it_converts_a_dam_asset_value_into_a_not_localized_pim_asset_value(): void
    {
        $damAsset = DamAssetBuilder::build('table', 'packshot', ['colors' => 'simic, blue, green']);
        $attribute = AssetAttributeBuilder::build('colors', 'multiple_options', false);
        $damAssetValue = new DamAssetValue('colors', 'simic, blue, green');

        $pimValue = $this->convert($damAsset, $damAssetValue, $attribute);
        $pimValue->getAttribute()->shouldReturn($attribute);
        $pimValue->getData()->shouldReturn(['simic', 'blue', 'green']);
        $pimValue->getLocale()->shouldReturn(null);
        $pimValue->getChannel()->shouldReturn(null);
    }
}
