<?php
declare(strict_types=1);

namespace Spec\AkeneoDAMConnector\Application\Mapping\AssetValueConverter;

use AkeneoDAMConnector\Application\Mapping\AssetValueConverter;
use AkeneoDAMConnector\Application\Mapping\AssetValueConverter\MediaLinkConverter;
use AkeneoDAMConnector\Domain\Model\Dam\DamAsset;
use AkeneoDAMConnector\Domain\Model\Dam\DamAssetIdentifier;
use AkeneoDAMConnector\Domain\Model\Dam\DamAssetValue;
use AkeneoDAMConnector\Domain\Model\FamilyCode;
use AkeneoDAMConnector\Domain\Model\Locale;
use AkeneoDAMConnector\Domain\Model\Pim\Attribute;
use AkeneoDAMConnector\Domain\Model\Pim\AttributeCode;
use PhpSpec\ObjectBehavior;
use Spec\AkeneoDAMConnector\Builder\AssetAttributeBuilder;

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
        $damAsset = new DamAsset(
            new DamAssetIdentifier('table'),
            new FamilyCode('packshot'),
            new Locale('en_US')
        );
        $damAsset->addValue('url', 'here.com');
        $attribute = new Attribute(new AttributeCode('url'), 'media_link', true);
        $damAssetValue = new DamAssetValue('url', 'here.com');

        $pimValue = $this->convert($damAsset, $damAssetValue, $attribute);
        $pimValue->getAttribute()->shouldReturn($attribute);
        $pimValue->getData()->shouldReturn('here.com');
        $pimValue->getLocale()->shouldReturn('en_US');
        $pimValue->getChannel()->shouldReturn(null);
    }

    public function it_converts_a_dam_asset_value_into_a_not_localized_pim_asset_value(): void
    {
        $damAsset = new DamAsset(
            new DamAssetIdentifier('table'),
            new FamilyCode('packshot'),
            null
        );
        $damAsset->addValue('url', 'here.com');
        $attribute = new Attribute(new AttributeCode('url'), 'media_link', false);
        $damAssetValue = new DamAssetValue('url', 'here.com');

        $pimValue = $this->convert($damAsset, $damAssetValue, $attribute);
        $pimValue->getAttribute()->shouldReturn($attribute);
        $pimValue->getData()->shouldReturn('here.com');
        $pimValue->getLocale()->shouldReturn(null);
        $pimValue->getChannel()->shouldReturn(null);
    }
}
