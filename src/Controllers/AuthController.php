<?php

namespace App\Controllers;

use App\Core\RestController;
use App\Models\User;

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
    }

    public function register()
    {
        $query = $this->getQuery();
        $query["password"] = password_hash($query["password"], PASSWORD_BCRYPT);

        $response = $this->model->insert($query);

        $this->returnJSON($response);
    }
}
