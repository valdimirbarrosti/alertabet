<?php
date_default_timezone_set('America/Sao_Paulo');

require __DIR__ . '/../vendor/autoload.php';
require_once(__DIR__ . '/../app/Helpers/functions.php');


$app = new App\App();
$app->run();
