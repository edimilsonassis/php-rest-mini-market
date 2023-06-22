<?php

use Symfony\Component\Dotenv\Dotenv;

require_once 'vendor/autoload.php';

date_default_timezone_set("America/Fortaleza");
setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');

const __ds = DIRECTORY_SEPARATOR;
const __env = __DIR__ . __ds;

$dotenv = new Dotenv();
$dotenv->load(__env . '/.env');

spl_autoload_register(function ($classe) {
    $file = __env . $classe . '.php';

    if (file_exists($file) && !is_dir($file))
        require_once $file;
    else
        throw new \Exception('Error when opening class ' . $classe, 1);
});