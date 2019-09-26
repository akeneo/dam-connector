<?php
declare(strict_types=1);

namespace Specification\AkeneoDAMConnector\Application\Mapping\AssetValueConverter;

use AkeneoDAMConnector\Application\Mapping\AssetValueConverter;
use AkeneoDAMConnector\Application\Mapping\AssetValueConverter\MediaLinkConverter;
use AkeneoDAMConnector\Domain\Asset\DamAssetValue;
use AkeneoDAMConnector\Tests\Specification\Builder\AssetAttributeBuilder;
use AkeneoDAMConnector\Tests\Specification\Builder\DamAssetBuilder;
use PhpSpec\ObjectBehavior;

class MediaLinkConverterSpec extends ObjectBehavior
{
    public function it_is_a_media_link_converter(): void
    {
        $this->shouldHaveType(MediaLinkConverter::class);
        $this->shouldImplement(AssetValueConverter::class);
    }

    public function it_provides_a_supported_type(): void
    {
        $this->getSupportedType()->shouldReturn('media_link');
    }

    public function it_converts_a_dam_asset_value_into_a_localized_pim_asset_value(): void
    {
        $damAsset = DamAssetBuilder::build('table', 'packshot', ['url' => 'here.com'], 'en_US');
        $attribute = AssetAttributeBuilder::build('url', 'media_link', true);
        $damAssetValue = new DamAssetValue('url', 'here.com');

        $pimValue = $this->convert($damAsset, $damAssetValue, $attribute);
        $pimValue->getAttribute()->shouldReturn($attribute);
        $pimValue->getData()->shouldReturn('here.com');
        $pimValue->getLocale()->shouldReturn('en_US');
        $pimValue->getChannel()->shouldReturn(null);
    }

    public function it_converts_a_dam_asset_value_into_a_not_localized_pim_asset_value(): void
    {
        $damAsset = DamAssetBuilder::build('table', 'packshot', ['url' => 'here.com']);
        $attribute = AssetAttributeBuilder::build('url', 'media_link', false);
        $damAssetValue = new DamAssetValue('url', 'here.com');

        $pimValue = $this->convert($damAsset, $damAssetValue, $attribute);
        $pimValue->getAttribute()->shouldReturn($attribute);
        $pimValue->getData()->shouldReturn('here.com');
        $pimValue->getLocale()->shouldReturn(null);
        $pimValue->getChannel()->shouldReturn(null);
    }
}
