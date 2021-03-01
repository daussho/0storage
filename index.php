<?php

require __DIR__ . '/vendor/autoload.php';

use App\Helpers\GlobalHelper;

$router = new AltoRouter();

// Router list

$router->map('GET', '/', 'App\Controllers\QueryController::fetch', 'fetch');
$router->map('POST', '/', 'App\Controllers\QueryController::insert', 'insert');

// match current request url
$match = $router->match();

// call closure or throw 404 status
if (is_array($match) && is_callable($match['target'])) {
    list($controller, $action) = explode('::', $match['target']);
    if (is_callable(array($controller, $action))) {
        $obj = new $controller();
        call_user_func_array(array($obj, $action), array($match['params']));
    } else {
        // here your routes are wrong.
        // Throw an exception in debug, send a  500 error in production
        GlobalHelper::returnJSON([
            "error" => "Method not found",
        ], 500);
    }
} else {
    // no route was matched
    GlobalHelper::returnJSON([
        "error" => "Not found",
    ], 404);
}

return null;
