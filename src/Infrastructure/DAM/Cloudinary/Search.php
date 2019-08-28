<?php

declare(strict_types=1);

namespace AkeneoDAMConnector\Infrastructure\DAM\Cloudinary;

class Search
{
    public function __construct(string $cloudName, string $apiKey, string $apiSecret)
    {
        \Cloudinary::config(array(
            'cloud_name' => $cloudName,
            'api_key' => $apiKey,
            'api_secret' => $apiSecret,
            'secure' => true
        ));
    }

    public function search(string $expression, array $withFields)
    {
        $searchEngine = new \Cloudinary\Search();
        $searchEngine->expression($expression);
        if (!empty($withFields)) {
            foreach ($withFields as $field) {
                $searchEngine->with_field($field);
            }
        }

        return $searchEngine->execute();
    }
}
