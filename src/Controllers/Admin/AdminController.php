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
            "password" => hash("sha256", $query["password"]),
            "email" => $query["email"],
            "name" => $query["name"]
        ]);

        $this->returnJSON($insert);
    }
}
