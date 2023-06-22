<?php

use http\middleware\Queue;

require_once __DIR__ . '/../../autoload.php';

header("Access-Control-Allow-Origin: *");

// Sample of default middleware 
// Queue::setDefault([
//     'maintenance'
// ]);

// Custom middleware 
Queue::setMap([
    'maintenance' => \http\middleware\Maintenance::class,
    'logged'      => \http\middleware\RequireLogin::class,
]);

// Default router for API route prefix 
$url = $_SERVER['HTTP_HOST'] ?? '';

echo "aaaaaaa $url";

new \routers\MarketServices("$url/api/");