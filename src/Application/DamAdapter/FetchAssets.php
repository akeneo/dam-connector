<?php

declare(strict_types=1);

namespace AkeneoDAMConnector\Application\DamAdapter;

use AkeneoDAMConnector\Domain\Model\FamilyCode;

interface FetchAssets
{
    /**
     * Fetches assets on DAM third party system depending on the last fetch date.
     */
    public function fetch(FamilyCode $assetFamilyCode, ?\DateTimeInterface $lastFetchDate): \Iterator;
}
