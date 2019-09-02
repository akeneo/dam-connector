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

namespace AkeneoDAMConnector\Infrastructure\StructureConfiguration;

use AkeneoDAMConnector\Application\StructureConfig\StructureConfigLoader;
use Symfony\Component\Yaml\Parser;

/**
 * @author Romain Monceau <romain@akeneo.com>
 */
class YamlFileStructureConfigLoader implements StructureConfigLoader
{
    public function __construct()
    {
        // TODO: inject kernel.project_dir parameters
    }

    public function load(): array
    {
        $filename = realpath(__DIR__ .'/../../../var/resources/pim-assets-structure.yml');

        $yamlParser = new Parser();
        $config = $yamlParser->parseFile($filename);

        return $config['structure'];
    }
}
