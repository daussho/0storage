<?php

namespace App\Controllers;

use App\Exceptions\ResponseException;
use App\Helpers\GlobalHelper;
use App\Helpers\SleekDBHelper;

class QueryController extends RestController
{
    private $store;

    public function __construct()
    {
        GlobalHelper::validateSchema([
            "app_name" => "required",
            "table" => "required",
            "operation" => "required",
        ], $this->getQuery());

        $this->store = SleekDBHelper::getStore($this->getQuery());
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
            case "edit":
                $this->edit();
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
        $param = $this->getQuery();

        switch ($param['operation']) {
            case "find_all":
                $data = $this->store->findAll();
                break;

            case "find_by_id":
                GlobalHelper::validateSchema([
                    "find_by_id.id" => "required|integer",
                ], $this->getQuery());

                $data = $this->store->findById(
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

                $data = $this->store->findBy(
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

                $data = $this->store->findOneBy(
                    $param['find_one_by']['criteria'],
                );
                break;
            default:
                throw new ResponseException("Invalid fetch operation", [], 400);
                break;
        }

        $this->returnJSON($data);
    }

    private function insert()
    {
        GlobalHelper::validateSchema([
            "data" => "required|array",
        ], $this->getQuery());

        if (GlobalHelper::isAssoc($this->getQuery("data"))) {
            $responseData = $this->store->insert($this->getQuery("data"));
        } else {
            $responseData = $this->store->insertMany($this->getQuery("data"));
        }

        $this->returnJSON($responseData);
    }

    private function edit()
    {
        $param = $this->getQuery();
        switch ($param['operation']) {
            case "update_by_id":
                GlobalHelper::validateSchema([
                    "update_by_id.id" => "required|integer",
                    "update_by_id.data" => "required|array",
                ], $this->getQuery());
                $update = $this->getQuery("update_by_id");

                $responseData = $this->store->updateById($update["id"], $update["data"]);
                break;

            case "update":
                GlobalHelper::validateSchema([
                    "update.data" => "required|array",
                ], $this->getQuery());

                $update = $this->getQuery("update");
                $responseData = $this->store->update($update["data"]);
                break;

            case "remove_fields_by_id":
                GlobalHelper::validateSchema([
                    "remove_fields_by_id.id" => "required|integer",
                    "remove_fields_by_id.data" => "required|array",
                ], $this->getQuery());
                $update = $this->getQuery("update");
                $responseData = $this->store->removeFieldsById($update["id"], $update["data"]);

                break;

            default:
                throw new ResponseException("Invalid edit operation", [], 400);
                break;
        }

        $this->returnJSON($responseData);
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
