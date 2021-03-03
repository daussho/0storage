<?php

namespace App\Controllers;

use App\Basic\WebController;

class AuthController extends WebController
{
    public function login()
    {
        $this->returnView("login", [
            "data" => [
                "name" => "test",
            ],
        ]);
    }
}
