<?php

declare(strict_types=1);

namespace AkeneoDAMConnector\Domain\Model\Dam;

/**
 * @author Romain Monceau <romain@akeneo.com>
 */
class DamAssetIdentifier
{
    private $identifier;

    public function __construct(string $identifier)
    {
        $this->identifier = $identifier;
    }

    public function __toString(): string
    {
        return $this->identifier;
    }
}
