<?php 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

function getBaseUrl()
{
    $baseUrl = sprintf('http://%s:%d', $_SERVER["SERVER_NAME"], $_SERVER["SERVER_PORT"]);
    // $baseUrl = "http://" . $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"];

    $requestUri = dirname($_SERVER["REQUEST_URI"]) . "?";

    if ($requestUri === '/?') {
        return $baseUrl . '/';
    }

    return $baseUrl . $requestUri;
}

$BASE_URL = getBaseUrl();
