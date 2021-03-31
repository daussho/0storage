<?php

declare(strict_types=1);

use App\Controllers\Admin\AdminController;
use App\Controllers\QueryController;
use App\Exceptions\ResponseException;
use App\Helpers\GlobalHelper;

$version = GlobalHelper::getAppVersion();

return [
    "$version" => GlobalHelper::generateRoute("$version", [
        [
            'POST',
            '/q/[a:document]?',
            function ($document) {
                if (!in_array($document, ["admin"])) {
                    (new QueryController($document))->dbQuery();
                }
                throw new ResponseException(403, "You don't have access");
            },
            'db_query'
        ],

        ['GET', '/auth/login', 'App\Controllers\AuthController::login', 'auth_login'],

        // Admin
        ['POST', '/admin/register', 'App\Controllers\Admin\AdminController::register', 'admin_register'],
        ['POST', '/admin/login', 'App\Controllers\Admin\AdminController::login', 'admin_login'],
    ]),
    "{$version}.a" => GlobalHelper::generateRoute("{$version}a", [
        ['POST', '/admin/login', function () {
            (new AdminController())->login();
        }, 'admin_login'],
    ]),
];
