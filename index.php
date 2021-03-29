<?php

require __DIR__ . '/vendor/autoload.php';

use App\Exceptions\ResponseException;
use App\Helpers\ResponseHelper;

$flag = $_GET['show_error_log'] ?? 0;
$msg = "Error, please contact administrator.";

try {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
    $dotenv->required(['DB_HASH', 'APP_NAME'])->notEmpty();

    $router = new AltoRouter();

    // Router list
    $router->addRoutes(generateRoute("v0", [
        ['POST', '/q', 'App\Controllers\QueryController::dbQuery', 'db_query'],
        ['GET', '/auth/login', 'App\Controllers\AuthController::login', 'auth_login'],

        // Admin
        ['POST', '/admin/register', 'App\Controllers\Admin\AdminController::register', 'admin_register'],
        ['POST', '/admin/login', 'App\Controllers\Admin\AdminController::login', 'admin_login'],
    ]));

    // Temp router
    $router->addRoutes(generateRoute("v0a", [
        ['POST', '/admin/register', 'App\Controllers\Admin\AdminController::registerNew', 'admin_register'],
    ]));

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
        throw new ResponseException("Route not found", [], 404);
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

function generateRoute(string $version, array $routes)
{
    foreach ($routes as $key => $value) {
        [$methods, $route, $action, $name] = $value;
        $routes[$key] = [
            $methods,
            "/$version$route",
            "$action",
            "$name\\_$version",
        ];
    }

    return $routes;
}
