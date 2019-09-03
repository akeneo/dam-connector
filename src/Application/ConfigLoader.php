<?php

declare(strict_types=1);

namespace AkeneoDAMConnector\Application;

interface ConfigLoader
{
    public function load(): array;
}
