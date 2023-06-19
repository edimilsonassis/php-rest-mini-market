<?php

require_once __DIR__ . '/../autoload.php';

// Default router for site
$url = $_SERVER['HTTP_HOST'] ?? '';

new \routers\MarketPages("$url");