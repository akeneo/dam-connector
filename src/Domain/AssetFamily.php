<?php

declare(strict_types=1);

namespace AkeneoDAMConnector\Domain;

class AssetFamily
{
    private $code;

    public function __construct(string $code)
    {
        $this->code = $code;
    }

    public function getCode(): string
    {
        return $this->code;
    }
}
