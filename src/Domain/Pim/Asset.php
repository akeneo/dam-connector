<?php

declare(strict_types=1);

namespace AkeneoDAMConnector\Domain\Pim;

class Asset
{
    private $code;

    private $values;

    public function __construct(string $code, $values = [])
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
                function (array $values, AssetValue $value) {
                    $values[$value->getAttribute()->getCode()] = $value->normalize();
                    return $values;
                },
                []
            ),
        ];
    }
}
