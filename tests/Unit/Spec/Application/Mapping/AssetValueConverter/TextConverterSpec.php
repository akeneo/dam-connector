<?php
declare(strict_types=1);

namespace Spec\AkeneoDAMConnector\Application\Mapping\AssetValueConverter;

use AkeneoDAMConnector\Application\Mapping\AssetValueConverter;
use AkeneoDAMConnector\Application\Mapping\AssetValueConverter\TextConverter;
use AkeneoDAMConnector\Domain\Model\Dam\DamAsset;
use AkeneoDAMConnector\Domain\Model\Dam\DamAssetIdentifier;
use AkeneoDAMConnector\Domain\Model\Dam\DamAssetValue;
use AkeneoDAMConnector\Domain\Model\FamilyCode;
use AkeneoDAMConnector\Domain\Model\Locale;
use AkeneoDAMConnector\Domain\Model\Pim\Attribute;
use AkeneoDAMConnector\Domain\Model\Pim\AttributeCode;
use PhpSpec\ObjectBehavior;
use Spec\AkeneoDAMConnector\Builder\AssetAttributeBuilder;

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
        $damAsset = $damAsset = new DamAsset(
            new DamAssetIdentifier('table'),
            new FamilyCode('packshot'),
            new Locale('en_US')
        );
        $damAsset->addValue('description', 'pretty');
        $attribute = new Attribute(new AttributeCode('description'), 'text', true);
        $damAssetValue = new DamAssetValue('description', 'pretty');

        $pimValue = $this->convert($damAsset, $damAssetValue, $attribute);
        $pimValue->getAttribute()->shouldReturn($attribute);
        $pimValue->getData()->shouldReturn('pretty');
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
        $damAsset->addValue('color', 'temur');
        $attribute = new Attribute(new AttributeCode('description'), 'text', false);
        $damAssetValue = new DamAssetValue('description', 'pretty');

        $pimValue = $this->convert($damAsset, $damAssetValue, $attribute);
        $pimValue->getAttribute()->shouldReturn($attribute);
        $pimValue->getData()->shouldReturn('pretty');
        $pimValue->getLocale()->shouldReturn(null);
        $pimValue->getChannel()->shouldReturn(null);
    }
}
