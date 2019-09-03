<?php

declare(strict_types=1);

namespace AkeneoDAMConnector\Application\Mapping;

class AssetValueConverterRegistry
{
    private $converters;

    public function __construct()
    {
        $this->converters = [];
    }

    public function registerConverter(AssetValueConverter $converter): void
    {
        $this->converters[$converter->getSupportedType()] = $converter;
    }

    public function getConverter(string $type): AssetValueConverter
    {
        if (null === $converter = $this->converters[$type]) {
            throw new \RuntimeException(
                sprintf('No asset value converter defined for the asset type "%s"', $type)
            );
        }

        return $converter;
    }
}
