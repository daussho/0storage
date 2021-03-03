<?php

namespace App\Controllers\Admin;

use App\Basic\WebController;

class AuthController extends WebController
{
    public function login()
    {
        $this->returnView("admin/login", []);
    }
}
