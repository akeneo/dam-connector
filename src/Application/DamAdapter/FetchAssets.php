<?php

declare(strict_types=1);

namespace AkeneoDAMConnector\Application\DamAdapter;

use AkeneoDAMConnector\Domain\AssetFamilyCode;

interface FetchAssets
{
    /**
     * Fetches assets on DAM third party system depending on the last fetch date.
     */
    public function fetch(AssetFamilyCode $assetFamilyCode, ?\DateTimeInterface $lastFetchDate): \Iterator;
}
