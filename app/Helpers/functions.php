<?php

function jsonFormatter($response)
{
    $jsonResponse = $response->getBody();
    $arrayResponse = json_decode($jsonResponse, true);
    $prettyJson = json_encode($arrayResponse, JSON_PRETTY_PRINT);
    header('Content-Type: application/json');
    return $prettyJson;
}


function debug($var)
{
    echo '<pre>';
    var_dump($var);
    echo '</pre>';
    die();
}


function env($key, $default = null)
{

    $envDir = getMainDir() . '\.env';
    if (!file_exists($envDir)) {
        return $default;
    }

    $contents = file_get_contents($envDir);

    $lines = explode("\n", $contents);
    foreach ($lines as $line) {
        if (empty($line) || strpos($line, '#') === 0) {
            continue;
        }
        list($envKey, $value) = explode('=', $line, 2);
        $envKey = trim($envKey);
        $value = trim($value);
        $value = trim($value, '"');
        if ($envKey === $key) {
            return $value;
        }
    }
    return $default;
}

function getMainDir()
{
    return dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR;
}
