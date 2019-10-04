<?php

declare(strict_types=1);

namespace AkeneoDAMConnector\Domain\Model\Dam;

/**
 * @author Willy Mesnage <willy.mesnage@akeneo.com>
 */
class DamAssetCollection implements \Iterator
{
    /** @var []DamAsset */
    private $damAssets;

    /** @var int */
    private $key;

    public function __construct()
    {
        $this->damAssets = [];
        $this->key = 0;
    }

    public function addAsset(DamAsset $asset)
    {
        array_push($this->damAssets, $asset);
    }

    /**
     * {@inheritdoc}
     */
    public function current(): ?DamAsset
    {
        return $this->damAssets[$this->key] ?? null;
    }

    /**
     * {@inheritdoc}
     */
    public function next(): void
    {
        $this->key++;
    }

    /**
     * {@inheritdoc}
     */
    public function key(): int
    {
        return $this->key;
    }

    /**
     * {@inheritdoc}
     */
    public function valid()
    {
        return isset($this->damAssets[$this->key]);
    }

    /**
     * {@inheritdoc}
     */
    public function rewind(): void
    {
        $this->key = 0;
    }
}
