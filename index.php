<?php

require __DIR__ . '/vendor/autoload.php';

use App\Exceptions\ResponseException;
use App\Helpers\GlobalHelper;

try {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
    $dotenv->required(['DB_HASH'])->notEmpty();

    $router = new AltoRouter();

    // Router list
    $router->addRoutes([
        ['GET', '/fetch', 'App\Controllers\QueryController::fetch', 'fetch'],
        ['GET', '/query', 'App\Controllers\QueryController::query', 'query'],
        ['POST', '/insert', 'App\Controllers\QueryController::insert', 'insert'],
        ['PUT', '/update', 'App\Controllers\QueryController::update', 'update'],
        ['DELETE', '/delete', 'App\Controllers\QueryController::update', 'delete'],
    ]);

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
            throw new ResponseException("Method not found", [], 500);
        }
    } else {
        // no route was matched
        throw new ResponseException("Not found", [], 404);
    }

    return null;
} catch (ResponseException $e) {

    GlobalHelper::returnJSON(
        [
            "message" => $e->getMessage(),
            "error" => $e->errorData(),
        ],
        $e->errorCode()
    );

} catch (Exception $e) {
    GlobalHelper::returnJSON([
        "error" => "Failed load env",
    ], 500);
}
