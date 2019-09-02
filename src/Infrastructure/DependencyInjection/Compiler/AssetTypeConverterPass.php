<?php

declare(strict_types=1);

namespace AkeneoDAMConnector\Infrastructure\DependencyInjection\Compiler;

use AkeneoDAMConnector\Application\Mapping\AssetValueConverterRegistry;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class AssetTypeConverterPass implements CompilerPassInterface
{
    public const SERVICE_TAG = 'app.asset_converter';

    public function process(ContainerBuilder $container): void
    {
        if (!$container->has(AssetValueConverterRegistry::class)) {
            return;
        }

        $definition = $container->findDefinition(AssetValueConverterRegistry::class);

        $taggedServices = $container->findTaggedServiceIds(self::SERVICE_TAG);
        foreach ($taggedServices as $id => $tags) {
            $definition->addMethodCall('registerConverter', [new Reference($id)]);
        }
    }
}
