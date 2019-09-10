<?php

declare(strict_types=1);

namespace AkeneoDAMConnector\Infrastructure\Persistence;

class SynchronizeAssetsExecutionRepository
{
    private $executions = [];

    public function save(Execution $execution): void
    {
        $this->executions[$execution->getFamilyCode()] = $execution;
    }

    /**
     * @param string[] $familyCodes
     * @return \DateTimeInterface[]
     */
    public function findLastSucceededExecutionTimeForFamilyCodes(array $familyCodes): array
    {
        return array_reduce(
            $familyCodes,
            function (array $executions, string $familyCode) {
                $executions[$familyCode] = null;

                return $executions;
            },
            []
        );
    }
}
