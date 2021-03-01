<?php

namespace App\Controllers;

use App\Helpers\GlobalHelper;
use App\Helpers\SleekDBHelper;

class QueryController
{
    private $query;

    public function __construct()
    {
        $this->query = GlobalHelper::getBody();
    }

    public function fetch()
    {
        $response = [];

        $requiredParam = [
            "app_name",
            "table",
            "operation",
        ];

        $errSchema = GlobalHelper::validateSchema($requiredParam, $this->query);

        if (!empty($errSchema)) {
            GlobalHelper::returnJSON([
                "error" => $errSchema,
            ], 400);
            return;
        }

        $checkSchema = [];
        if ($this->query['operation'] == "find") {
            $checkSchema = GlobalHelper::validateSchema(array_merge($requiredParam, [
                "find",
            ]), $this->query);

            if (empty($checkSchema)) {
                $response = SleekDBHelper::find($this->query);
            }
        }

        if (!empty($checkSchema)) {
            GlobalHelper::returnJSON([
                "error" => $checkSchema,
            ], 400);
            return;
        }

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
