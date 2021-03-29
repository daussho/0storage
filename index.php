<?php

require __DIR__ . '/vendor/autoload.php';

use App\Exceptions\ResponseException;
use App\Helpers\ResponseHelper;

$flag = $_GET['show_error_log'] ?? 0;
$msg = "Error, please contact administrator.";

$route = include(__DIR__ . "/src/Settings/route.php");

try {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
    $dotenv->required(['DB_HASH', 'APP_NAME'])->notEmpty();

    $router = new AltoRouter();

    // Router list
    $router->addRoutes($route['v0']);

    // Temp router
    $router->addRoutes($route['v0a']);

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
            throw new ResponseException(500, "Method not found");
        }
    } else {
        // no route was matched
        throw new ResponseException(404, "Route not found");
    }

    return null;
} catch (ResponseException $e) {
    if ($flag == 0) {
        ResponseHelper::returnJSON([
            "message" => $msg,
        ]);
    } else {
        ResponseHelper::returnJSON(
            [
                "code" => $e->errorCode(),
                "message" => $e->getMessage(),
                "error" => $e->errorData(),
            ],
            $e->errorCode()
        );
    }
} catch (Exception $e) {
    if ($flag == 1) {
        $msg = $e->getMessage();
    }

    ResponseHelper::returnJSON([
        "message" => $msg,
    ], 500);
}
