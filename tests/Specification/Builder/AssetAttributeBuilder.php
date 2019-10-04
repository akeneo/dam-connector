<?php
declare(strict_types=1);

namespace AkeneoDAMConnector\Tests\Specification\Builder;

use AkeneoDAMConnector\Domain\Model\Pim\Attribute;
use AkeneoDAMConnector\Domain\Model\Pim\AttributeCode;

class AssetAttributeBuilder
{
    public static function build(string $code, string $type, bool $isLocalizable = false): Attribute
    {
        return new Attribute(new AttributeCode($code), $type, $isLocalizable);
    }
}
