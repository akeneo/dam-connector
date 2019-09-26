<?php
declare(strict_types=1);

namespace Specification\AkeneoDAMConnector\Application\Mapping\AssetValueConverter;

use AkeneoDAMConnector\Application\Mapping\AssetValueConverter;
use AkeneoDAMConnector\Application\Mapping\AssetValueConverter\TextConverter;
use AkeneoDAMConnector\Domain\Asset\DamAsset;
use AkeneoDAMConnector\Domain\Asset\DamAssetValue;
use AkeneoDAMConnector\Domain\AssetAttribute;
use AkeneoDAMConnector\Domain\Locale;
use AkeneoDAMConnector\Tests\Specification\Builder\AssetAttributeBuilder;
use AkeneoDAMConnector\Tests\Specification\Builder\DamAssetBuilder;
use PhpSpec\ObjectBehavior;

class TextConverterSpec extends ObjectBehavior
{
    public function it_is_a_text_converter(): void
    {
        $this->shouldHaveType(TextConverter::class);
        $this->shouldImplement(AssetValueConverter::class);
    }

    public function it_provides_a_supported_type(): void
    {
        $this->getSupportedType()->shouldReturn('text');
    }

    public function it_converts_a_dam_asset_value_into_a_localized_pim_asset_value(): void
    {
        $damAsset = DamAssetBuilder::build('table', 'packshot', ['description' => 'pretty'], 'en_US');
        $attribute = AssetAttributeBuilder::build('description', 'text', true);
        $damAssetValue = new DamAssetValue('description', 'pretty');

        $pimValue = $this->convert($damAsset, $damAssetValue, $attribute);
        $pimValue->getAttribute()->shouldReturn($attribute);
        $pimValue->getData()->shouldReturn('pretty');
        $pimValue->getLocale()->shouldReturn('en_US');
        $pimValue->getChannel()->shouldReturn(null);
    }

    public function it_converts_a_dam_asset_value_into_a_not_localized_pim_asset_value(): void
    {
        $damAsset = DamAssetBuilder::build('table', 'packshot', ['description' => 'pretty']);
        $attribute = AssetAttributeBuilder::build('description', 'text', false);
        $damAssetValue = new DamAssetValue('description', 'pretty');

        $pimValue = $this->convert($damAsset, $damAssetValue, $attribute);
        $pimValue->getAttribute()->shouldReturn($attribute);
        $pimValue->getData()->shouldReturn('pretty');
        $pimValue->getLocale()->shouldReturn(null);
        $pimValue->getChannel()->shouldReturn(null);
    }
}
