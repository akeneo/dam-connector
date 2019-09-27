<?php

declare(strict_types=1);

namespace AkeneoDAMConnector\Domain\Model\Dam;

/**
 * @author Romain Monceau <romain@akeneo.com>
 */
class DamAssetValue
{
    private $property;
    private $value;

    public function __construct(string $property, string $value)
    {
        $this->property = $property;
        $this->value = $value;
    }

    public function property(): string
    {
        return $this->property;
    }

    public function value(): string
    {
        return $this->value;
    }
}
