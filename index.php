<?php

require __DIR__ . '/vendor/autoload.php';

require_once("src/Autoloader.php");

use helpers\GlobalHelper;
use helpers\SleekDBHelper;

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
    "app_name",
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

$tableName = hash("crc32", $query['app_name']) . "_" . $query['table'];
$store = new \SleekDB\Store($tableName, $dataDir, $configuration);

if ($query['operation'] == "insert"){
    $response[] = SleekDBHelper::insertParser($store, $query['data']);
}

GlobalHelper::returnJSON($response);