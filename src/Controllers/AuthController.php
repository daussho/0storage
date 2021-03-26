<?php

namespace App\Controllers;

use App\Core\WebController;

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
