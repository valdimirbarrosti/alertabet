<?php

$dbConfig = [
    'driver' => 'pgsql ',
    'host' => '127.0.0.1',
    'database' => 'barrosmvc',
    'username' => 'postgres',
    'password' => 'postgres',
    'charset' => 'utf8',
    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ],
];
