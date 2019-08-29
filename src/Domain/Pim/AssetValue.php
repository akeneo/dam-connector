<?php

declare(strict_types=1);

namespace AkeneoDAMConnector\Domain\Pim;

class AssetValue
{
    private $attribute;

    private $data;

    private $locale;

    private $channel;

    public function __construct(AssetAttribute $attribute, string $data, string $locale = null, string $channel = null)
    {
        $this->attribute = $attribute;
        $this->data = $data;
        $this->locale = $locale;
        $this->channel = $channel;
    }

    public function getAttribute(): AssetAttribute
    {
        return $this->attribute;
    }

    public function getData(): string
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
