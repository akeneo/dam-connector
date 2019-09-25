<?php
declare(strict_types=1);

namespace Specification\AkeneoDAMConnector\Application\Service;

use AkeneoDAMConnector\Application\ConfigLoader;
use AkeneoDAMConnector\Application\PimAdapter\UpdateAssetStructure;
use AkeneoDAMConnector\Application\Service\SynchronizeAssetsStructure;
use PhpSpec\ObjectBehavior;

class SynchronizeAssetsStructureSpec extends ObjectBehavior
{
    public function let(
        ConfigLoader $structureConfigLoader,
        UpdateAssetStructure $updateAssetStructure
    ): void {
        $this->beConstructedWith($structureConfigLoader, $updateAssetStructure);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(SynchronizeAssetsStructure::class);
    }

    function it_synchronizes_assets_structure(
        $structureConfigLoader,
        $updateAssetStructure
    ) {
        $config = $this->getConfig();
        $structureConfigLoader->load()->willReturn($config);

        $packshotData = [
            'code' => 'packshot',
            'product_link_rules' => [
                [
                    'product_selections' => [
                        'field' => 'sku',
                        'operator' => 'EQUALS',
                        'value' => '{{product_ref}}',
                    ],
                ]
            ]
        ];
        $userInstructionData = [
            'code' => 'user_instruction',
            'product_link_rules' => [
                [
                    'product_selections' => [
                        'field' => 'sku',
                        'operator' => 'EQUALS',
                        'value' => '{{product_ref}}',
                    ],
                ]
            ]
        ];

        $updateAssetStructure->upsertFamily('packshot', ['code' => 'packshot'])->shouldBeCalled();
        $updateAssetStructure->upsertFamily('packshot', $packshotData)->shouldBeCalled();

        $updateAssetStructure->upsertFamily('user_instruction', ['code' => 'user_instruction'])->shouldBeCalled();
        $updateAssetStructure->upsertFamily('user_instruction', $userInstructionData)->shouldBeCalled();

        $updateAssetStructure->upsertAttribute('packshot', 'locale', ['code' => 'locale', 'type' => 'text'])->shouldBeCalled();
        $updateAssetStructure->upsertAttribute('packshot', 'product_ref', ['code' => 'product_ref', 'type' => 'text'])->shouldBeCalled();

        $updateAssetStructure->upsertAttribute('user_instruction', 'locale', ['code' => 'locale', 'type' => 'text'])->shouldBeCalled();
        $updateAssetStructure->upsertAttribute('user_instruction', 'product_ref', ['code' => 'product_ref', 'type' => 'text'])->shouldBeCalled();

        $this->execute();
    }

    private function getConfig(): array
    {
        return [
            'packshot' => [
                'product_link_rules' => [
                    'product_selections' => [
                        'field' => 'sku',
                        'operator' => 'EQUALS',
                        'value' => '{{product_ref}}',
                    ],
                ],
                'attributes' => [
                    [
                        'code' => 'locale',
                        'type' => 'text'
                    ],
                    [
                        'code' => 'product_ref',
                        'type' => 'text'
                    ],
                ],
            ],
            'user_instruction' => [
                'product_link_rules' => [
                    'product_selections' => [
                        'field' => 'sku',
                        'operator' => 'EQUALS',
                        'value' => '{{product_ref}}',
                    ],
                ],
                'attributes' => [
                    [
                        'code' => 'locale',
                        'type' => 'text'
                    ],
                    [
                        'code' => 'product_ref',
                        'type' => 'text'
                    ],
                ],
            ],
        ];
    }
}
