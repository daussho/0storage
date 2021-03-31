<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

class User extends Model
{
    public function __construct()
    {
        $rules = [
            "email" => "required|email|unique:email",
            "username" => "required|unique:username",
            "password" => "required",
            "name" => "required",
        ];

        parent::__construct("user", $rules);
    }
}