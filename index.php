<?php

require __DIR__ . '/vendor/autoload.php';

// require_once "src/Autoloader.php";

use App\Helpers\GlobalHelper;

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

$router->map('POST', '/', 'App\Controllers\QueryController::index', 'query');

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
