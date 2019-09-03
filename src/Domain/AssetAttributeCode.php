<?php

declare(strict_types=1);

namespace AkeneoDAMConnector\Domain;

class AssetAttributeCode
{
    private $code;

    public function __construct(string $code)
    {
        $this->code = $code;
    }

    public function __toString(): string
    {
        return $this->code;
    }
}
