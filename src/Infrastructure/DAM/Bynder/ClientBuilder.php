<?php

declare(strict_types=1);

namespace AkeneoDAMConnector\Infrastructure\DAM\Bynder;

use \Bynder\Api\BynderApiFactory;
use Bynder\Api\Impl\BynderApi;

class ClientBuilder
{
    /** @var BynderApi */
    private $client;

    public function __construct(
        string $consumerKey,
        string $consumerSecret,
        string $token,
        string $tokenSecret,
        string $baseUrl
    ) {
        $this->client = BynderApiFactory::create(
            [
                'consumerKey' => $consumerKey,
                'consumerSecret' => $consumerSecret,
                'token' => $token,
                'tokenSecret' => $tokenSecret,
                'baseUrl' => $baseUrl
            ]
        );
    }

    public function getClient(): BynderApi
    {
        return $this->client;
    }
}
