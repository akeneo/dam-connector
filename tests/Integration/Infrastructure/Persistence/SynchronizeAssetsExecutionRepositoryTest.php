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

namespace AkeneoDAMConnector\Tests\Integration\Infrastructure\Persistence;

use AkeneoDAMConnector\Infrastructure\Persistence\Execution;
use AkeneoDAMConnector\Infrastructure\Persistence\SynchronizeAssetsExecutionRepository;
use AkeneoDAMConnector\Tests\Integration\Infrastructure\IntegrationTestCase;
use Doctrine\DBAL\Connection;

/**
 * @author Romain Monceau <romain@akeneo.com>
 */
class SynchronizeAssetsExecutionRepositoryTest extends IntegrationTestCase
{
    public function testSave()
    {
        $execution = new Execution('shoes', Execution::STATUS_PENDING, new \DateTimeImmutable('2019-08-28'));
        $this->getRepository()->save($execution);

        $dataExecution = $this->getDbalConnection()->fetchAssoc(
            'SELECT * FROM synchronize_assets_execution'
        );
        $this->assertEquals('shoes', $dataExecution['family_code']);
    }

    private function getRepository(): SynchronizeAssetsExecutionRepository
    {
        return $this->get(SynchronizeAssetsExecutionRepository::class);
    }

    private function getDbalConnection(): Connection
    {
        return $this->get('Doctrine\DBAL\Connection');
    }
}
