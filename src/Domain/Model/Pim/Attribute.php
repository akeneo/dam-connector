<?php

declare(strict_types=1);

namespace AkeneoDAMConnector\Domain\Model\Pim;

class Attribute
{
    private $code;

    private $type;

    private $localizable;

    public function __construct(AttributeCode $code, string $type, bool $localizable)
    {
        $this->code = $code;
        $this->type = $type;
        $this->localizable = $localizable;
    }

    public function getCode(): AttributeCode
    {
        return $this->code;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function isLocalizable(): bool
    {
        return $this->localizable;
    }
}
