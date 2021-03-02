<?php

namespace App\Controllers;

class RestController
{
    protected function getQuery()
    {
        $query = file_get_contents('php://input');

        if (empty($query)) {
            $query = [];
        } else {
            $query = json_decode($query, true);
        }

        return $query;
    }

    public function returnJSON($data, $statusCode = 200): void
    {
        header("Content-Type: application/json");
        http_response_code($statusCode);
        echo json_encode([
            "code" => $statusCode,
            "success" => $statusCode === 200,
            "data" => $data,
            "timestamp" => date(DATE_ISO8601),
        ]);
    }
}
