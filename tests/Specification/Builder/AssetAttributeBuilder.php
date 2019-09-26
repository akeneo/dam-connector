<?php
declare(strict_types=1);

namespace AkeneoDAMConnector\Tests\Specification\Builder;

use AkeneoDAMConnector\Domain\AssetAttribute;
use AkeneoDAMConnector\Domain\AssetAttributeCode;

class AssetAttributeBuilder
{
    public static function build(string $code, string $type, bool $isLocalizable = false): AssetAttribute
    {
        return new AssetAttribute(new AssetAttributeCode($code), $type, $isLocalizable);
    }
}
