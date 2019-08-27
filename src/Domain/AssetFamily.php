<?php

declare(strict_types=1);

namespace AkeneoDAMConnector\Domain;

class AssetFamily
{
    /** @var string */
    private $code;

    /** @var array */
    private $labels;

    public function __construct(string $code, array $labels = [])
    {
        $this->code = $code;
        $this->labels = $labels;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getLabels(): array
    {
        return $this->labels;
    }
}
