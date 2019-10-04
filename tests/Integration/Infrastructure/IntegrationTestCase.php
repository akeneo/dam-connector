<?php

declare(strict_types=1);

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
