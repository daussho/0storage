<?php

namespace App\Controllers;

class RestController
{
    /**
     * @param string|null $key
     *
     * @return mixed
     */
    protected function getQuery(string $key = null)
    {
        $query = file_get_contents('php://input');

        if (empty($query)) {
            return null;
        }

        $query = json_decode($query, true);

        if (empty($key)) {
            return $query;
        }

        return $query[$key];
    }

    /**
     * @param mixed $data
     * @param int $statusCode
     *
     * @return void
     */
    protected function returnJSON($data, $statusCode = 200): void
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
