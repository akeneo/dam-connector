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

namespace AkeneoDAMConnector\Tests\Unit\Domain\Asset;

use AkeneoDAMConnector\Domain\Model\Dam\DamAsset;
use AkeneoDAMConnector\Domain\Model\Dam\DamAssetIdentifier;
use AkeneoDAMConnector\Domain\Model\FamilyCode;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

/**
 * @author Romain Monceau <romain@akeneo.com>
 */
class DamAssetTest extends TestCase
{
    public function testCanBeInstanciated()
    {
        $this->assertInstanceOf(
            DamAsset::class,
            new DamAsset(
                new DamAssetIdentifier('foo'),
                new FamilyCode('bar'),
                null
            )
        );
    }
}
