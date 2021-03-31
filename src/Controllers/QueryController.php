<?php

namespace App\Controllers;

use App\Core\Model;
use App\Core\RestController;
use App\Helpers\GlobalHelper;
use Rakit\Validation\Validator;

class QueryController extends RestController
{
    private $store;

    public function __construct(string $document)
    {
        // GlobalHelper::validateSchema([
        //     "app_name" => "required",
        //     "table" => "required",
        //     "query" => "required",
        // ], $this->getQuery());

        $this->document = $document;

        // $this->store = SleekDBHelper::getStore($this->document);
        $this->store = new Model($this->document);
    }

    public function dbQuery()
    {
        $query = $this->getQuery("query");

        $data = call_user_func_array(
            [
                $this->store,
                $this->getQuery("action")
            ],
            $this->getQuery("param") ? $this->getQuery("param") : []
        );
        $this->returnJSON($data);
        return;

        GlobalHelper::validateSchema([
            "name" => [
                "required",
                (new Validator())("in", [
                    "find",
                    "insert",
                    "edit",
                    "delete",
                    "query_builder",
                ]),
            ],
        ], $query);

        switch ($query['name']) {
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
                // $this->delete();
                break;
            case "query_builder":
                // $this->queryBuilder();
                break;
        }
    }

    private function fetch()
    {
        $param = $this->getQuery();
        $query = $param['query'];

        $data = $this->store->findBy(
            $query['param']['criteria'] ?? ["_id", ">", 0],
            $query['param']['order_by'],
            $query['param']['limit'] ?? 25,
            $query['param']['offset'],
        );

        $this->returnJSON($data);
    }

    private function insert()
    {
        $query = $this->getQuery("query");

        $responseData = $this->store->insertMany($query['insert']);

        $this->returnJSON($responseData);
    }

    private function edit()
    {
        $query = $this->getQuery('query');

        GlobalHelper::validateSchema([
            "operation" => [
                (new Validator())("in", [
                    "update_by_id",
                    "update",
                    "remove_fields_by_id",
                ]),
            ],
        ], $query);

        $update = $query[$query['operation']];
        switch ($query['operation']) {
            case "update_by_id":
                GlobalHelper::validateSchema([
                    "id" => "required|integer",
                    "data" => "required|array",
                ], $update);

                $responseData = $this->store->updateById($update["id"], $update["data"]);
                break;

            case "update":
                GlobalHelper::validateSchema([
                    "data" => "required|array",
                ], $update);

                $success = $this->store->update($update["data"]);
                if ($success) {
                    $responseData = $update['data'];
                }
                break;

            case "remove_fields_by_id":
                // GlobalHelper::validateSchema([
                //     "remove_fields_by_id.id" => "required|integer",
                //     "remove_fields_by_id.data" => "required|array",
                // ], $this->getQuery());
                // $update = $this->getQuery("update");

                // $responseData = $this->store->removeFieldsById($update["id"], $update["data"]);
                // break;
        }

        $this->returnJSON($responseData);
    }

    private function delete()
    {
        $param = $this->getQuery();

        GlobalHelper::validateSchema([
            "operation" => [
                "required",
                (new Validator())("in", [
                    "delete_by",
                    "delete_by_id",
                ]),
            ],
        ], $param);

        switch ($param["operation"]) {
            case "delete_by":
                GlobalHelper::validateSchema([
                    "delete_by.criteria" => "required|array",
                    "delete_by.return_option" => [
                        "required",
                        (new Validator())("in", [
                            "DELETE_RETURN_BOOL",
                            "DELETE_RETURN_RESULTS",
                            "DELETE_RETURN_COUNT",
                        ]),
                    ],
                ], $this->getQuery());
                $delete = $this->getQuery("delete_by");

                $responseData = $this->store->deleteBy($delete["criteria"], $delete["return_option"]);
                break;
            case "delete_by_id":
                GlobalHelper::validateSchema([
                    "delete_by_id.id" => "required|integer",
                ], $this->getQuery());
                $delete = $this->getQuery("delete_by_id");

                $responseData = $this->store->deleteById($delete["id"]);
                break;
        }

        $this->returnJSON($responseData);
    }

    private function queryBuilder()
    {
        $this->returnJSON([
            "Message" => "Not implemented",
        ]);
    }
}
