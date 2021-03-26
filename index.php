<?php

require __DIR__ . '/vendor/autoload.php';

use App\Exceptions\ResponseException;
use App\Helpers\GlobalHelper;
use App\Helpers\ResponseHelper;

$flag = $_GET['show_error_log'] ?? 0;
$msg = "Error, please contact administrator.";

try {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
    $dotenv->required(['DB_HASH'])->notEmpty();

    $router = new AltoRouter();

    // Router list
    $router->addRoutes([
        ['POST', '/q', 'App\Controllers\QueryController::dbQuery', 'db_query'],
        ['GET', '/auth/login', 'App\Controllers\AuthController::login', 'auth_login'],
    ]);

    $router->addRoutes([
        ['POST', '/admin/register', 'App\Controllers\Admin\AdminController::register', 'admin_register'],
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
    if ($flag == 0) {
        ResponseHelper::returnJSON([
            "message" => $msg,
        ]);
    } else {
        ResponseHelper::returnJSON(
            [
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
