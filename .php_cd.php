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
    /**
     * DOMAIN
     */
    $builder->only([
        'AkeneoDAMConnector\Domain',
    ])->in('AkeneoDAMConnector\Domain'),

    /**
     * APPLICATION
     */
    $builder->only([
        'AkeneoDAMConnector\Application',
        'AkeneoDAMConnector\Domain',
    ])->in('AkeneoDAMConnector\Application'),

    /**
     * INFRASTRUCTURE
     */
    $builder->only([
        'AkeneoDAMConnector\Application\DamAdapter',
        'AkeneoDAMConnector\Domain',
        'Bynder\Api',
    ])->in('AkeneoDAMConnector\Infrastructure\DAM\Bynder'),

    $builder->only([
        'AkeneoDAMConnector\Application\DamAdapter',
        'AkeneoDAMConnector\Domain',
    ])->in('AkeneoDAMConnector\Infrastructure\DAM\Cloudinary'),

    $builder->only([
        'AkeneoDAMConnector\Application\DamAdapter',
        'AkeneoDAMConnector\Domain',
    ])->in('AkeneoDAMConnector\Infrastructure\DAM\Fake'),

    $builder->only([
        'AkeneoDAMConnector\Application',
        'AkeneoDAMConnector\Domain',
        'AkeneoDAMConnector\Infrastructure\Persistence',
        'Symfony\Component\Console',
    ])->in('AkeneoDAMConnector\Infrastructure\DAM\Command'),

    $builder->only([
        'AkeneoDAMConnector\Domain',
        'Doctrine\DBAL',
    ])->in('AkeneoDAMConnector\Infrastructure\Persistence'),

    $builder->only([
        'AkeneoDAMConnector\Application\Mapping\AssetValueConverterRegistry',
        'Symfony\Component\DependencyInjection',
    ])->in('AkeneoDAMConnector\Infrastructure\DependencyInjection'),

    $builder->only([
        'AkeneoDAMConnector\Application\PimAdapter',
        'AkeneoDAMConnector\Domain',
        'Akeneo\Pim\ApiClient\Exception',
        'Akeneo\PimEnterprise\ApiClient\Api\AssetManager',
        'Akeneo\PimEnterprise\ApiClient\AkeneoPimEnterpriseClientBuilder',
        'Akeneo\PimEnterprise\ApiClient\AkeneoPimEnterpriseClientInterface',
    ])->in('AkeneoDAMConnector\Infrastructure\Pim'),
];

return new Configuration($rules, $finder);
