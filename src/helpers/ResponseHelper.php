<?php

declare(strict_types=1);

namespace App\Helpers;

class ResponseHelper
{
    /**
     * Send HTTP JSON response
     *
     * @param mixed $data
     * @param int $statusCode
     *
     * @return void
     */
    public static function returnJSON($data, $statusCode = 200): void
    {
        header("Content-Type: application/json");
        http_response_code($statusCode);
        echo json_encode($data);
    }
}
