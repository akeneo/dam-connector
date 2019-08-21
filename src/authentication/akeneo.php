<?php

declare(strict_types=1);

require_once('vendor/autoload.php');

use Akeneo\PimEnterprise\ApiClient;

$clientBuilder = new ApiClient\AkeneoPimEnterpriseClientBuilder('http://localhost:8080/');
$client = $clientBuilder->buildAuthenticatedByPassword(
    getenv('AKENEO_CLIENT_ID'),
    getenv('AKENEO_SECRET'),
    getenv('AKENEO_USERNAME'),
    getenv('AKENEO_PASSWORD')
);
echo $client->getToken();

//$client = $clientBuilder->buildAuthenticatedByToken(getenv('AKENEO_CLIENT_ID'), getenv('AKENEO_SECRET'), $tokenAdmin, $refreshTokenAdmin);