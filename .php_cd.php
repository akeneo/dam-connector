<?php

declare(strict_types=1);

use Akeneo\CouplingDetector\Configuration\Configuration;
use Akeneo\CouplingDetector\Configuration\DefaultFinder;
use Akeneo\CouplingDetector\RuleBuilder;

$finder = new DefaultFinder();
$builder = new RuleBuilder();

$finder->files()->name('*.php');

$builder = new RuleBuilder();

$rules = [
    $builder->only([
        'AkeneoDAMConnector\Domain',
    ])->in('AkeneoDAMConnector\Domain'),

    $builder->only([
        'AkeneoDAMConnector\Domain',
        'AkeneoDAMConnector\Application',
    ])->in('AkeneoDAMConnector\Application'),
];

return new Configuration($rules, $finder);
