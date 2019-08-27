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

namespace AkeneoDAMConnector\Domain;

/**
 * @author Romain Monceau <romain@akeneo.com>
 */
class Assets
{
    private $assets;

    public function __construct()
    {
        $this->assets = [];
    }

    public function add(Asset $asset): void
    {
        $this->assets[] = $asset;
    }
}
