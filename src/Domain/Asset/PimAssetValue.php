<?php

declare(strict_types=1);

namespace AkeneoDAMConnector\Domain\Asset;

use AkeneoDAMConnector\Domain\AssetAttributeCode;

class PimAssetValue
{
    private $attributeCode;

    private $data;

    private $locale;

    private $channel;

    /**
     * @param $data string|string[]
     */
    public function __construct(AssetAttributeCode $attributeCode, $data, string $locale = null, string $channel = null)
    {
        $this->attributeCode = $attributeCode;
        $this->data = $data;
        $this->locale = $locale;
        $this->channel = $channel;
    }

    public function getAttributeCode(): AssetAttributeCode
    {
        return $this->attributeCode;
    }

    /**
     * @return string|string[]
     */
    public function getData()
    {
        return $this->data;
    }

    public function getLocale(): string
    {
        return $this->locale;
    }

    public function getChannel(): string
    {
        return $this->channel;
    }

    public function normalize(): array
    {
        return [
            'locale' => $this->locale,
            'channel' => $this->channel,
            'data' => $this->data
        ];
    }
}
