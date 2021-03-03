<?php

namespace App\Controllers;

use App\Exceptions\ResponseException;
use App\Helpers\GlobalHelper;
use App\Helpers\SleekDBHelper;

class QueryController extends RestController
{
    public function __construct()
    {
        GlobalHelper::validateSchema([
            "app_name" => "required",
            "table" => "required",
            "operation" => "required",
        ], $this->getQuery());
    }

    public function dbQuery()
    {
        $queryType = $_GET['query'];

        switch ($queryType) {
            case "find":
                $this->fetch();
                break;
            case "insert":
                $this->insert();
                break;
            case "update":
                $this->update();
                break;
            case "delete":
                $this->delete();
                break;
            case "query_builder":
                $this->queryBuilder();
                break;
            default:
                throw new ResponseException("Query not found", [], 400);
                break;
        }
    }

    private function fetch()
    {
        // $response = SleekDBHelper::find($this->getQuery());

        $param = $this->getQuery();
        $store = SleekDBHelper::getStore($param);

        switch ($param['operation']) {
            case "find_all":
                $data = $store->findAll();
                break;

            case "find_by_id":
                GlobalHelper::validateSchema([
                    "find_by_id.id" => "required|integer",
                ], $this->getQuery());

                $data = $store->findById(
                    $param['find_by_id']['id']
                );
                break;

            case "find_by":
                GlobalHelper::validateSchema([
                    "find_by.criteria" => "required|array",
                    "find_by.order_by" => "required",
                    "find_by.limit" => "required|integer",
                    "find_by.offset" => "required|integer",
                ], $this->getQuery());

                $data = $store->findBy(
                    $param['find_by']['criteria'],
                    $param['find_by']['order_by'],
                    $param['find_by']['limit'],
                    $param['find_by']['offset'],
                );
                break;

            case "find_one_by":
                GlobalHelper::validateSchema([
                    "find_by.criteria" => "required|array",
                ], $this->getQuery());

                $data = $store->findOneBy(
                    $param['find_one_by']['criteria'],
                );
                break;
        }

        $this->returnJSON($data);
    }

    private function insert()
    {
        $response = SleekDBHelper::insertParser($this->getQuery());

        $this->returnJSON($response);
    }

    private function update()
    {
        $response = SleekDBHelper::update($this->getQuery());

        $this->returnJSON($response);
    }

    private function delete()
    {

    }

    private function queryBuilder()
    {
        $response = SleekDBHelper::queryBuilder($this->getQuery());

        $this->returnJSON($response);
    }
}
