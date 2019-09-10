<?php

declare(strict_types=1);

namespace AkeneoDAMConnector\Application\Service;

use AkeneoDAMConnector\Application\PimAdapter\GetAssetStructure;
use AkeneoDAMConnector\Application\PimAdapter\UpdateAssetStructure;
use AkeneoDAMConnector\Domain\AssetFamilyCode;

/**
 * @author Willy Mesnage <willy.mesnage@akeneo.com>
 */
class SynchronizeAttributeOptions
{
    /** @var GetAssetStructure */
    private $getAssetStructureApi;

    /** @var UpdateAssetStructure */
    private $updateAssetStructureApi;

    /** @var []PimAssetValue */
    private $optionsFromPim;

    public function __construct(
        GetAssetStructure $getAssetStructureApi,
        UpdateAssetStructure $updateAssetStructureApi
    ) {
        $this->getAssetStructureApi = $getAssetStructureApi;
        $this->updateAssetStructureApi = $updateAssetStructureApi;
        $this->optionsFromPim = [];
    }

    public function execute(AssetFamilyCode $familyCode, array $options): void
    {
        $optionsToUpsert = [];
        foreach ($options as $option) {
            $attributeCode = (string) $option->getAttributeCode();
            if (!isset($this->optionsFromPim[$attributeCode])) {
                $this->optionsFromPim[$attributeCode] =
                    $this->getAssetStructureApi->getAttributeOptionList($familyCode, $attributeCode);
            }

            if (!isset($optionsToUpsert[$attributeCode])) {
                $optionsToUpsert[$attributeCode] = [];
            }

            if (!in_array($option->getData(), $this->optionsFromPim[$attributeCode]) &&
                !in_array($option->getData(), $optionsToUpsert[$attributeCode])
            ) {
                $optionsToUpsert[$attributeCode][] = $option->getData();
            }
        }

        foreach ($optionsToUpsert as $attributeCode => $options) {
            $data = array_map(function ($code) {
                return ['code' => $code];
            }, $options);
            $this->updateAssetStructureApi->upsertAttributeOptions((string) $familyCode, $attributeCode, $data);
        }
    }
}
