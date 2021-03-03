<?php

namespace App\Controllers;

use App\Basic\View;

class AuthController
{
    public function login()
    {
        $view = new View("login");
        $view->assign("data", [
            "name" => "John",
        ]);
    }
}
