<?php

require __DIR__ . '/vendor/autoload.php';

// require_once "src/Autoloader.php";

use App\Helpers\GlobalHelper;
use App\Helpers\SleekDBHelper;

$dataDir = __DIR__ . "/mydb";

$requestMethod = $_SERVER['REQUEST_METHOD'];
// if ($requestMethod !== "POST") {
//     GlobalHelper::returnJSON([
//         "error" => "Invalid request",
//     ], 400);
//     return null;
// }

$router = new AltoRouter();

$router->map('GET', '/', function () {
    GlobalHelper::returnJSON([
        "error" => "Invalid request",
    ], 400);
}, 'home');

$router->map('POST', '/', function () {
    $response = [];

    $requiredParam = [
        "app_name",
        "table",
        "operation",
    ];

    $query = file_get_contents('php://input');

    if (empty($query)) {
        $query = [];
    } else {
        $query = json_decode($query, true);
    }

    $errSchema = GlobalHelper::validateSchema($requiredParam, $query);

    if (!empty($errSchema)) {
        GlobalHelper::returnJSON([
            "error" => $errSchema,
        ], 400);
        return;
    }

    $checkSchema = [];
    if ($query['operation'] == "insert") {
        $checkSchema = GlobalHelper::validateSchema(array_merge($requiredParam, []), $query);

        if (empty($checkSchema)) {
            $response = SleekDBHelper::insertParser($query);
        }
    } else if ($query['operation'] == "find") {
        $checkSchema = GlobalHelper::validateSchema(array_merge($requiredParam, [
            "find",
        ]), $query);

        if (empty($checkSchema)) {
            $response = SleekDBHelper::find($query);
        }
    } else if ($query['operation'] == "update") {
        $checkSchema = GlobalHelper::validateSchema(array_merge($requiredParam, [
            "update",
            "id",
            "data",
        ]), $query);

        if (empty($checkSchema)) {
            $response = SleekDBHelper::update($query);
        }
    } else if ($query['operation'] == "query_builder") {
        $checkSchema = GlobalHelper::validateSchema(array_merge($requiredParam, [
            // "select",
            // "where",
            // "search",
            // "skip",
            // "order_by"
        ]), $query);

        if (empty($checkSchema)) {
            $response = SleekDBHelper::queryBuilder($query);
        }
    }

    if (!empty($checkSchema)) {
        GlobalHelper::returnJSON([
            "error" => $checkSchema,
        ], 400);
        return;
    }

    GlobalHelper::returnJSON($response);
}, 'query');

// match current request url
$match = $router->match();

// call closure or throw 404 status
if (is_array($match) && is_callable($match['target'])) {
    call_user_func_array($match['target'], $match['params']);
} else {
    // no route was matched
    header($_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
}

return null;
