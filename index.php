<?php

declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use App\Exceptions\ResponseException;
use App\Helpers\GlobalHelper;
use App\Helpers\ResponseHelper;


$msg = "Error, please contact administrator.";

$route = include(__DIR__ . "/src/Settings/route.php");

try {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
    $dotenv->required(['DB_HASH', 'APP_NAME'])->notEmpty();

    $flag = $_ENV["SHOW_ERROR_LOG"];
    if (!empty($_GET['show_error_log'])) {
        $flag = $_GET['show_error_log'];
    }

    $version = GlobalHelper::getAppVersion();
    $router = new AltoRouter();

    // Router list
    $router->addRoutes($route[$version]);

    // Temp router
    $router->addRoutes($route["{$version}.a"]);

    // match current request url
    $match = $router->match();

    // call closure or throw 404 status
    if (is_array($match) && is_callable($match['target'])) {
        if (is_object($match['target'])) {
            call_user_func_array($match['target'], $match['params']);
        } else {
            [$controller, $action] = explode('::', $match['target']);
            if (is_callable(array($controller, $action))) {
                $obj = new $controller();
                call_user_func_array(array($obj, $action), array($match['params']));
            } else {
                // here your routes are wrong.
                // Throw an exception in debug, send a  500 error in production
                throw new ResponseException(500, "Method not found");
            }
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
                "trace" => $e->getTrace(),
            ],
            $e->errorCode()
        );
    }
} catch (\Throwable $e) {
    $err = [
        "message" => $msg
    ];

    if ($flag == 1) {
        $err = [
            "message" => $e->getMessage(),
            "trace" => $e->getTrace(),
        ];
    }

    ResponseHelper::returnJSON($err, 500);
}
