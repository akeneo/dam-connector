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

namespace AkeneoDAMConnector\Tests\Integration\Infrastructure;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @author Romain Monceau <romain@akeneo.com>
 */
class IntegrationTestCase extends KernelTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        static::bootKernel(['debug' => false]);
    }

    protected function get(string $serviceId)
    {
        return static::$container->get($serviceId);
    }
}
