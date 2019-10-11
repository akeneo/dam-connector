<?php
declare(strict_types=1);

namespace Spec\AkeneoDAMConnector\Application\Mapping\AssetValueConverter;

use AkeneoDAMConnector\Application\Mapping\AssetValueConverter;
use AkeneoDAMConnector\Application\Mapping\AssetValueConverter\MultiOptionConverter;
use AkeneoDAMConnector\Domain\Model\Dam\DamAsset;
use AkeneoDAMConnector\Domain\Model\Dam\DamAssetIdentifier;
use AkeneoDAMConnector\Domain\Model\Dam\DamAssetValue;
use AkeneoDAMConnector\Domain\Model\FamilyCode;
use AkeneoDAMConnector\Domain\Model\Locale;
use AkeneoDAMConnector\Domain\Model\Pim\Attribute;
use AkeneoDAMConnector\Domain\Model\Pim\AttributeCode;
use PhpSpec\ObjectBehavior;
use Spec\AkeneoDAMConnector\Builder\AssetAttributeBuilder;

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
        $damAsset = new DamAsset(
            new DamAssetIdentifier('table'),
            new FamilyCode('packshot'),
            new Locale('en_US')
        );
        $damAsset->addValue('colors', 'simic, blue, green');

        $attribute = new Attribute(new AttributeCode('colors'), 'multiple_options', true);
        $damAssetValue = new DamAssetValue('colors', 'simic, blue, green');

        $pimValue = $this->convert($damAsset, $damAssetValue, $attribute);
        $pimValue->getAttribute()->shouldReturn($attribute);
        $pimValue->getData()->shouldReturn(['simic', 'blue', 'green']);
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
        $damAsset->addValue('colors', 'simic, blue, green');
        $attribute = new Attribute(new AttributeCode('colors'), 'multiple_options', false);
        $damAssetValue = new DamAssetValue('colors', 'simic, blue, green');

        $pimValue = $this->convert($damAsset, $damAssetValue, $attribute);
        $pimValue->getAttribute()->shouldReturn($attribute);
        $pimValue->getData()->shouldReturn(['simic', 'blue', 'green']);
        $pimValue->getLocale()->shouldReturn(null);
        $pimValue->getChannel()->shouldReturn(null);
    }
}
