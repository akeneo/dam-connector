<?php
declare(strict_types=1);

namespace Specification\AkeneoDAMConnector\Application\Mapping;

use AkeneoDAMConnector\Application\ConfigLoader;
use AkeneoDAMConnector\Application\Mapping\AssetAttributeBuilder;
use AkeneoDAMConnector\Domain\AssetAttributeCode;
use AkeneoDAMConnector\Domain\AssetFamilyCode;
use AkeneoDAMConnector\Domain\Exception\AttributeMappingNotFound;
use AkeneoDAMConnector\Domain\Exception\FamilyMappingNotFound;
use AkeneoDAMConnector\Tests\Specification\Builder\AssetAttributeBuilder as TestAttributeBuilder;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class AssetMapperSpec extends ObjectBehavior
{
    public function let(ConfigLoader $mappingConfigLoader, AssetAttributeBuilder $assetAttributeBuilder): void
    {
        $this->beConstructedWith($mappingConfigLoader, $assetAttributeBuilder);
    }

    public function it_provides_mapped_properties_of_a_family($mappingConfigLoader): void
    {
        $mappingConfigLoader->load()->willReturn($this->getMapping());
        $familyCode = new AssetFamilyCode('packshot');

        $this->getMappedProperties($familyCode)->shouldReturn(['sku', 'url', 'colors']);
    }

    public function it_throws_an_exception_if_no_mapping_can_be_provided($mappingConfigLoader): void
    {
        $mappingConfigLoader->load()->willReturn($this->getMapping());
        $familyCode = new AssetFamilyCode('family');

        $this
            ->shouldThrow(
                new FamilyMappingNotFound()
            )
            ->during('getMappedProperties', [$familyCode]);
    }

    public function it_maps_a_dam_property_to_a_pim_attribute(
        $mappingConfigLoader,
        $assetAttributeBuilder
    ): void {
        $mappingConfigLoader->load()->willReturn($this->getMapping());
        $familyCode = new AssetFamilyCode('packshot');
        $attribute = TestAttributeBuilder::build('preview', 'text');

        $assetAttributeBuilder
            ->build('packshot', Argument::that(function ($argument) {
                return $argument instanceof AssetAttributeCode &&
                    'preview' === $argument->__toString();
            }))
            ->shouldBeCalled()
            ->willReturn($attribute);

        $this->mapAttribute($familyCode, 'url')->shouldReturn($attribute);
    }

    public function it_throws_an_exception_if_the_dam_property_does_not_match_a_pim_attribute(
        $mappingConfigLoader
    ): void {
        $mappingConfigLoader->load()->willReturn($this->getMapping());
        $familyCode = new AssetFamilyCode('packshot');

        $this
            ->shouldThrow(
                new AttributeMappingNotFound()
            )
            ->during('mapAttribute', [$familyCode, 'roustifouette']);
    }

    private function getMapping(): array
    {
        return [
            'packshot' => [
                'sku' => 'product_ref',
                'url' => 'preview',
                'colors' => 'main_colors',
            ],
            'user_instruction' => [
                'sku' => 'product_ref',
                'url' => 'media_link',
                'pages' => 'number_of_pages',
            ],
        ];
    }
}
