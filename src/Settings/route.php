<?php

use App\Helpers\GlobalHelper;

return [
    "v0" => GlobalHelper::generateRoute("v0", [
        ['POST', '/q', 'App\Controllers\QueryController::dbQuery', 'db_query'],
        ['GET', '/auth/login', 'App\Controllers\AuthController::login', 'auth_login'],

        // Admin
        ['POST', '/admin/register', 'App\Controllers\Admin\AdminController::register', 'admin_register'],
        ['POST', '/admin/login', 'App\Controllers\Admin\AdminController::login', 'admin_login'],
    ]),
    "v0a" => GlobalHelper::generateRoute("v0a", [
        ['POST', '/admin/register', 'App\Controllers\Admin\AdminController::registerNew', 'admin_register'],
        ['POST', '/admin/all', 'App\Controllers\Admin\AdminController::getAll', 'admin_all'],
    ])
];