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

        if (!empty($errSchema)) {
            GlobalHelper::returnJSON([
                "error" => $errSchema,
            ], 400);
            die(1);
        }
    }

    public function fetch()
    {
        $response = SleekDBHelper::find($this->getQuery());

        GlobalHelper::returnJSON($response);
    }

    public function insert()
    {
        if ($this->query['operation'] == "insert") {
            $checkSchema = GlobalHelper::validateSchema(array_merge($requiredParam, []), $this->query);

            if (empty($checkSchema)) {
                $response = SleekDBHelper::insertParser($this->query);
            }
        }
    }

    public function update()
    {
        if ($this->query['operation'] == "update") {
            $checkSchema = GlobalHelper::validateSchema(array_merge($requiredParam, [
                "update",
                "id",
                "data",
            ]), $this->query);

            if (empty($checkSchema)) {
                $response = SleekDBHelper::update($this->query);
            }
        }
    }

    public function delete()
    {

    }

    public function query()
    {
        if ($this->query['operation'] == "query_builder") {
            $checkSchema = GlobalHelper::validateSchema(array_merge($requiredParam, [
                // "select",
                // "where",
                // "search",
                // "skip",
                // "order_by"
            ]), $this->query);

            if (empty($checkSchema)) {
                $response = SleekDBHelper::queryBuilder($this->query);
            }
        }
    }
}
