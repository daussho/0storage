<?php

require __DIR__ . '/vendor/autoload.php';

require_once("src/Autoloader.php");

use \helpers\GlobalHelper;

$dataDir = __DIR__ . "/mydb";

$requestMethod = $_SERVER['REQUEST_METHOD'];
if ($requestMethod !== "POST") {
    GlobalHelper::returnJSON([
        "error" => "Invalid request"
    ], 400);
    return;
}

$response = [];

$configuration = [
    "auto_cache" => true,
    "cache_lifetime" => null,
    "timeout" => 120,
    "primary_key" => "_id"
];

$requiredQuery = [
    "table",
    "operation",
    "data"
];

$query = file_get_contents('php://input');

if (empty($query)){
    $query = [];
} else {
    $query = json_decode($query, true);
}

$errSchema = GlobalHelper::validatePost($requiredQuery, $query);

if (!empty($errSchema)) {
    GlobalHelper::returnJSON([
        "error" => $errSchema
    ], 400);
    return;
}

$newsStore = new \SleekDB\Store($query['table'], $dataDir, $configuration);

if ($query['operation'] == "insert"){
    $response[] = $newsStore->insert($query['data']);
}

GlobalHelper::returnJSON($response);