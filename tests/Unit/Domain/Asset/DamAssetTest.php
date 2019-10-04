<?php

declare(strict_types=1);

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
