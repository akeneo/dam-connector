<?php

declare(strict_types=1);

namespace AkeneoDAMConnector\Infrastructure\Persistence;

class Execution
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_RUNNING = 'running';
    public const STATUS_SUCCESS = 'success';

    private $familyCode;
    private $status;
    private $startTime;

    public function __construct(string $familyCode, string $status, \DateTimeInterface $startTime)
    {
        $this->familyCode = $familyCode;
        $this->status = $status;
        $this->startTime = $startTime;
    }

    public static function create(string $familyCode): self
    {
        return new self($familyCode, self::STATUS_PENDING, new \DateTimeImmutable('now'));
    }

    public function run(): self
    {
        $this->status = self::STATUS_RUNNING;

        return $this;
    }

    public function succeeded(): self
    {
        $this->status = self::STATUS_SUCCESS;

        return $this;
    }

    public function getFamilyCode(): string
    {
        return $this->familyCode;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getStartTime(): \DateTimeInterface
    {
        return $this->startTime;
    }
}
