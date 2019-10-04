<?php

declare(strict_types=1);

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
