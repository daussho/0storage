<?php

namespace App\Controllers\Admin;

use App\Core\RestController;
use App\Helpers\GlobalHelper;
use App\Helpers\SleekDBHelper;
use SleekDB\Store;

class AdminController extends RestController
{
    /**
     * @var Store
     */
    private $store;

    public function __construct()
    {
        $this->store = SleekDBHelper::getStore([
            "app_name" => "admin",
            "table" => "users",
        ]);
    }

    public function register()
    {
        $query = $this->getQuery();

        GlobalHelper::validateSchema([
            "username" => "required",
            "password" => "required",
            "email" => "required|email",
            "name" => "required",
        ], $query);

        $found = $this->store->findOneBy([
            ["username", "=", $query["username"]],
            ["email", "=", $query["email"]],
        ]);

        if (!empty($found)) {
            $this->returnJSON([
                "message" => "Duplicate username or password!"
            ], 500);
            return;
        }

        $insert = $this->store->insert([
            "username" => $query["username"],
            "password" => password_hash($query["password"], PASSWORD_BCRYPT),
            "email" => $query["email"],
            "name" => $query["name"]
        ]);

        $this->returnJSON($insert);
    }

    public function login()
    {
        $query = $this->getQuery();

        GlobalHelper::validateSchema([
            "username" => "required",
            "password" => "required",
        ], $query);

        $user = $this->store->findOneBy([
            ["username", "=", $query["username"]],
        ]);

        if (empty($user)) {
            $this->returnJSON([
                "message" => "Username not found!"
            ], 404);
            return;
        }

        if (!password_verify($query["password"], $user["password"])) {
            $this->returnJSON([
                "message" => "Wrong password!"
            ], 400);
            return;
        }

        $this->returnJSON($user);
    }
}
