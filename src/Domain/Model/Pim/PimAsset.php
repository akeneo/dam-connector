<?php

declare(strict_types=1);

namespace AkeneoDAMConnector\Domain\Model\Pim;

use AkeneoDAMConnector\Domain\Model\FamilyCode;

class PimAsset
{
    private $code;

    /** @var PimAssetValue[] */
    private $values;

    /** @var FamilyCode */
    private $familyCode;

    public function __construct(string $code, FamilyCode $familyCode, array $values = [])
    {
        $this->code = $code;
        $this->values = $values;
        $this->familyCode = $familyCode;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function normalize(): array
    {
        return [
            'code' => $this->code,
            'values' => array_reduce(
                $this->values,
                function (array $values, PimAssetValue $value) {
                    $values[(string)$value->getAttributeCode()][] = $value->normalize();

                    return $values;
                },
                []
            ),
        ];
    }

    public function getAttributeOptions(): array
    {
        $options = [];
        foreach ($this->values as $value) {
            if (in_array($value->getAttribute()->getType(), ['single_option', 'multiple_options'])) {
                $options[] = $value;
            }
        }

        return $options;
    }
}
