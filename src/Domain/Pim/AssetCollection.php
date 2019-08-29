<?php

declare(strict_types=1);

namespace AkeneoDAMConnector\Domain\Pim;

class AssetCollection implements \Iterator
{
    private $key = 0;

    private $assets;

    public function __construct(array $assets = [])
    {
        $this->assets = $assets;
    }

    public function normalize(): array
    {
        return array_map(function (Asset $asset) {
            return $asset->normalize();
        }, $this->assets);
    }

    public function current(): Asset
    {
        return $this->assets[$this->key];
    }

    public function next(): int
    {
        return ++$this->key;
    }

    public function key(): int
    {
        return $this->key;
    }

    public function valid(): bool
    {
        return isset($this->assets[$this->key]);
    }

    public function rewind(): void
    {
        $this->key = 0;
    }
}
