<?php

declare(strict_types=1);

namespace AkeneoDAMConnector\Infrastructure\Persistence;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\PDOConnection;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Types\Type;

class SynchronizeAssetsExecutionRepository
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function save(Execution $execution): void
    {
        $this->connection->insert(
            'synchronize_assets_execution',
            [
                'family_code' => $execution->getFamilyCode(),
                'status' => $execution->getStatus(),
                'start_time' => $execution->getStartTime(),
            ],
            [
                'start_time' => Type::DATETIME_IMMUTABLE
            ]
        );
    }

    /**
     * @param string[] $familyCodes
     * @return \DateTimeInterface[]
     */
    public function findLastSucceededExecutionTimeForFamilyCodes(array $familyCodes): array
    {
        $status = Execution::STATUS_SUCCESS;
        $query = <<<SQL
            select family_code, max(start_time) as start_time
            from synchronize_assets_execution
            where status = '$status'
            and family_code in (?)
            group by family_code
SQL;

        $results = $this->connection->executeQuery(
            $query,
            [
                $familyCodes,
            ],
            [
                Connection::PARAM_STR_ARRAY,
            ]
        )->fetchAll(FetchMode::ASSOCIATIVE);

        $executionTimes = [];
        foreach ($familyCodes as $familyCode) {
            $executionTimes[$familyCode] = null;
        }
        foreach ($results as $row) {
            $executionTimes[$row['family_code']] = new \DateTimeImmutable($row['start_time']);
        }

        return $executionTimes;
    }
}
