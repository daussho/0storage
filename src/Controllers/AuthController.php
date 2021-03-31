<?php

namespace App\Controllers;

use App\Core\RestController;
use App\Exceptions\ResponseException;
use App\Helpers\GlobalHelper;
use App\Models\User;
use Firebase\JWT\JWT;

class AuthController extends RestController
{
    /**
     * @var User
     */
    private $model;

    public function __construct()
    {
        $this->model =  new User();
    }

    public function login()
    {
        $query = $this->getQuery();

        GlobalHelper::validateSchema([
            "username" => "required",
            "password" => "required",
        ], $query);

        $user = $this->model->findOneBy(["username", "=", $query["username"]]);

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

    public function register()
    {
        $query = $this->getQuery();
        $query["password"] = password_hash($query["password"], PASSWORD_BCRYPT);

        $response = $this->model->insert($query);

        $this->exclude(["_id", "password"])->returnJSON($response);
    }
}
