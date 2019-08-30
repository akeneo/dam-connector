<?php

declare(strict_types=1);

namespace AkeneoDAMConnector\Domain;

/**
 * @author Willy Mesnage <willy.mesnage@akeneo.com>
 */
class ResourceType
{
    /** @var string */
    private $resourceType;

    public function __construct(string $resourceType)
    {
        if (!in_array($resourceType, ['image', 'other'])) {
            $resourceType = 'other';
        }
        $this->resourceType = $resourceType;
    }

    public function __toString(): string
    {
        return $this->resourceType;
    }
}
