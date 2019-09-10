<?php

declare(strict_types=1);

namespace AkeneoDAMConnector\Domain\Asset;

class PimAsset
{
    private $code;

    /** @var PimAssetValue[] */
    private $values;

    public function __construct(string $code, array $values = [])
    {
        $this->code = $code;
        $this->values = $values;
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

    public function getValuesWithOptions(): array
    {
        $valuesWithOptions = [];
        foreach ($this->values as $value) {
            if (in_array($value->getAttribute()->getType(), ['single_option', 'multiple_options'])) {
                $valuesWithOptions[] = $value;
            }
        }

        return $valuesWithOptions;
    }
}
