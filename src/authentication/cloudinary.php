<?php

declare(strict_types=1);

require_once('vendor/autoload.php');

$return = Cloudinary::config(array(
    'cloud_name' => getenv('CLOUDINARY_CLOUD_NAME'),
    'api_key' => getenv('CLOUDINARY_API_KEY'),
    'api_secret' => getenv('CLOUDINARY_API_SECRET'),
    'secure' => true
));

$search = new Cloudinary\Search();
$results = $search->expression('test')->execute();
var_dump($results);