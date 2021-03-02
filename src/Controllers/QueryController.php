<?php

namespace App\Controllers;

use App\Helpers\GlobalHelper;
use App\Helpers\SleekDBHelper;

class QueryController extends RestController
{
    public function __construct()
    {
        $requiredParam = [
            "app_name",
            "table",
            "operation",
        ];

        $errSchema = GlobalHelper::validateSchema($requiredParam, $this->getQuery());

        // if (!empty($errSchema)) {
        //     $this->returnJSON([
        //         "error" => $errSchema,
        //     ], 400);
        //     die(1);
        // }
    }

    public function fetch()
    {
        // $response = SleekDBHelper::find($this->getQuery());

        $param = $this->getQuery();
        $store = SleekDBHelper::getStore($param);

        switch ($param['operation']) {
            case "find_all":
                $data = $store->findAll();
                break;
            case "find_by_id":
                $data = $store->findById(
                    $param['find_by_id']['id']
                );
                break;
            case "find_by":
                $data = $store->findBy(
                    $param['find_by']['criteria'],
                    $param['find_by']['order_by'],
                    $param['find_by']['limit'],
                    $param['find_by']['offset'],
                );
                break;
            case "find_one_by":
                $data = $store->findOneBy(
                    $param['find_one_by']['criteria'],
                );
                break;
        }

        $this->returnJSON($data);
    }

    public function insert()
    {
        $response = SleekDBHelper::insertParser($this->getQuery());

        $this->returnJSON($response);
    }

    public function update()
    {
        $response = SleekDBHelper::update($this->getQuery());

        $this->returnJSON($response);
    }

    public function delete()
    {

    }

    public function query()
    {
        $response = SleekDBHelper::queryBuilder($this->getQuery());

        $this->returnJSON($response);
    }
}
