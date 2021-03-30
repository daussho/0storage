<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\RestController;
use App\Exceptions\ResponseException;
use App\Helpers\GlobalHelper;
use App\Models\Admin;
use Firebase\JWT\JWT;

class AdminController extends RestController
{
    /**
     * @var Admin
     */
    private $store;

    public function __construct()
    {
        $this->store = new Admin();
    }

    public function register(): void
    {
        $query = $this->getQuery();

        $insert = $this->store->insert([
            "username" => $query["username"],
            "password" => password_hash($query["password"], PASSWORD_BCRYPT),
            "email" => $query["email"],
            "name" => $query["name"]
        ]);

        $this->exclude(["password"])->returnJSON($insert);
    }

    public function login(): void
    {
        $query = $this->getQuery();

        GlobalHelper::validateSchema([
            "username" => "required",
            "password" => "required",
        ], $query);

        $user = $this->store->findOneBy(["username", "=", $query["username"]]);

        if (empty($user)) {
            throw new ResponseException(404, "Username not found");
            return;
        }

        if (!password_verify($query["password"], $user["password"])) {
            throw new ResponseException(400, "Wrong password!");
        }

        $user['token'] = JWT::encode($user, $_ENV['JWT_KEY']);

        $this->exclude(["_id", "password"])->returnJSON($user);
    }
}
