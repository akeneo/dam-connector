<?php
declare(strict_types=1);

namespace Specification\AkeneoDAMConnector\Application\Mapping;

use AkeneoDAMConnector\Application\ConfigLoader;
use AkeneoDAMConnector\Application\Mapping\AssetAttributeBuilder;
use AkeneoDAMConnector\Domain\Model\Pim\AttributeCode;
use AkeneoDAMConnector\Domain\Model\FamilyCode;
use PhpSpec\ObjectBehavior;

class AssetAttributeBuilderSpec extends ObjectBehavior
{
    public function let(ConfigLoader $structureConfigLoader): void
    {
        $this->beConstructedWith($structureConfigLoader);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(AssetAttributeBuilder::class);
    }

    function it_builds_an_attribute($structureConfigLoader): void
    {
        $structureConfigLoader->load()->willReturn($this->getConfig());

        $familyCode = new FamilyCode('packshot');
        $attributeCode = new AttributeCode('locale');

        $attribute = $this->build($familyCode, $attributeCode);
        $attribute->getCode()->shouldReturn($attributeCode);
        $attribute->getType()->shouldReturn('text');
        $attribute->isLocalizable()->shouldReturn(false);
    }

    function it_throws_an_exception_if_attribute_does_not_exist($structureConfigLoader): void
    {
        $structureConfigLoader->load()->willReturn($this->getConfig());

        $familyCode = new FamilyCode('packshot');
        $attributeCode = new AttributeCode('description');

        $this
            ->shouldThrow(
                new \RuntimeException()
            )
            ->during('build', [$familyCode, $attributeCode]);
    }

    private function getConfig(): array
    {
        return [
            'packshot' => [
                'attributes' => [
                    [
                        'code' => 'locale',
                        'type' => 'text'
                    ],
                ],
            ],
        ];
    }
}
