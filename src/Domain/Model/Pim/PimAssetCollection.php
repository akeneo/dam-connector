<?php

declare(strict_types=1);

namespace AkeneoDAMConnector\Domain\Model\Pim;

class PimAssetCollection implements \Iterator
{
    private $key = 0;

    private $assets;

    public function addAsset(PimAsset $asset): void
    {
        $this->assets[] = $asset;
    }

    public function normalize(): array
    {
        return array_map(function (PimAsset $asset) {
            return $asset->normalize();
        }, $this->assets);
    }

    public function current(): PimAsset
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
