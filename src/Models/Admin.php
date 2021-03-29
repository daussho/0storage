<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use App\Helpers\SleekDBHelper;
use ReflectionClass;

class Admin extends Model
{
    public function __construct()
    {
        $storeName = (new ReflectionClass($this))->getShortName();
        $store = [
            "storeName" => $storeName,
            "databasePath" => SleekDBHelper::getAppDir(),
            "configuration" => SleekDBHelper::getDBConfig()
        ];

        $rules = [
            "email" => "required|email|unique:email",
            "username" => "required|unique:username",
            "password" => "required",
            "name" => "required",
        ];

        parent::__construct($storeName, $rules);
    }
}
