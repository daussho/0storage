<?php

namespace App\Controllers;

use App\Helpers\GlobalHelper;
use App\Helpers\SleekDBHelper;

class QueryController
{
    public function index()
    {
        $response = [];

        $requiredParam = [
            "app_name",
            "table",
            "operation",
        ];

        $query = file_get_contents('php://input');

        if (empty($query)) {
            $query = [];
        } else {
            $query = json_decode($query, true);
        }

        $errSchema = GlobalHelper::validateSchema($requiredParam, $query);

        if (!empty($errSchema)) {
            GlobalHelper::returnJSON([
                "error" => $errSchema,
            ], 400);
            return;
        }

        $checkSchema = [];
        if ($query['operation'] == "insert") {
            $checkSchema = GlobalHelper::validateSchema(array_merge($requiredParam, []), $query);

            if (empty($checkSchema)) {
                $response = SleekDBHelper::insertParser($query);
            }
        } else if ($query['operation'] == "find") {
            $checkSchema = GlobalHelper::validateSchema(array_merge($requiredParam, [
                "find",
            ]), $query);

            if (empty($checkSchema)) {
                $response = SleekDBHelper::find($query);
            }
        } else if ($query['operation'] == "update") {
            $checkSchema = GlobalHelper::validateSchema(array_merge($requiredParam, [
                "update",
                "id",
                "data",
            ]), $query);

            if (empty($checkSchema)) {
                $response = SleekDBHelper::update($query);
            }
        } else if ($query['operation'] == "query_builder") {
            $checkSchema = GlobalHelper::validateSchema(array_merge($requiredParam, [
                // "select",
                // "where",
                // "search",
                // "skip",
                // "order_by"
            ]), $query);

            if (empty($checkSchema)) {
                $response = SleekDBHelper::queryBuilder($query);
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
}
