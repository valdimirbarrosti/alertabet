<?php

ini_set('display_errors', '0');
setlocale(LC_TIME, 'pt_BR');
date_default_timezone_set('America/Sao_Paulo');
set_time_limit(60);


require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'Helpers' . DIRECTORY_SEPARATOR . 'functions.php';

$app = new App\App();
$app->run();
