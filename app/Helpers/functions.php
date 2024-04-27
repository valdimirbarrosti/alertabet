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
