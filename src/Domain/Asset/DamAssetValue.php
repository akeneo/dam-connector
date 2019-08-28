<?php

declare(strict_types=1);

/*
 * This file is part of the Akeneo PIM Enterprise Edition.
 *
 * (c) 2019 Akeneo SAS (http://www.akeneo.com)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AkeneoDAMConnector\Domain\Asset;

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
