<?php

declare(strict_types=1);

namespace AkeneoDAMConnector\Infrastructure;

use AkeneoDAMConnector\Application\ConfigLoader;
use Symfony\Component\Yaml\Parser;

class YamlConfigLoader implements ConfigLoader
{
    private $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function load(): array
    {
        $yamlParser = new Parser();

        return $yamlParser->parseFile($this->path);
    }
}
