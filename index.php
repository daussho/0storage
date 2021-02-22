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



$requiredParam = [
    "app_name",
    "table",
    "operation",
];

$query = file_get_contents('php://input');

if (empty($query)){
    $query = [];
} else {
    $query = json_decode($query, true);
}

$errSchema = GlobalHelper::validateSchema($requiredParam, $query);

if (!empty($errSchema)) {
    GlobalHelper::returnJSON([
        "error" => $errSchema
    ], 400);
    return;
}

// $tableName = hash("crc32", $query['app_name']) . "_" . $query['app_name']. "_" . $query['table'];
// $store = new Store($tableName, $dataDir, $configuration);

$checkSchema = [];
if ($query['operation'] == "insert"){
    $checkSchema = GlobalHelper::validateSchema(array_merge($requiredParam, []), $query);
    
    if (empty($checkSchema)){
        $response[] = SleekDBHelper::insertParser($query);
    }
} else if ($query['operation'] == "query_builder"){
    $checkSchema = GlobalHelper::validateSchema(array_merge($requiredParam, [
        "select",
        "where",
        "search",
        "skip",
        "order_by"
    ]), $query);
    
    if (empty($checkSchema)){
        $response[] = SleekDBHelper::queryBuilder($query);
    }
}

if (!empty($checkSchema)){
    GlobalHelper::returnJSON([
        "error" => $checkSchema
    ], 400);
    return;
}

GlobalHelper::returnJSON($response);