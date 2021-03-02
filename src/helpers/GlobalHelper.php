<?php

namespace App\Helpers;

use App\Exceptions\ResponseException;

/**
 * Global helper
 */
class GlobalHelper
{

    /**
     * Validate required field
     *
     * @param array $requiredFields
     * @param array $post
     *
     * @return array
     */
    public static function validateSchema(array $requiredFields, array $post): array
    {
        $validation = [];

        foreach ($requiredFields as $required => $key) {
            if (!array_key_exists($key, $post)) {
                $validation['required'][] = $key;
            }
        }

        if (!empty($validation)) {
            throw new ResponseException("Error schema", $validation, 400);
        }

        return $validation;
    }

    /**
     * Send HTTP JSON response
     *
     * @param mixed $data
     * @param int $statusCode
     *
     * @return void
     * @deprecated
     */
    public static function returnJSON($data, $statusCode = 200): void
    {
        header("Content-Type: application/json");
        http_response_code($statusCode);
        echo json_encode($data);
    }

    /**
     * Get query body
     *
     * @return array
     */
    public static function getBody(): array
    {
        $query = file_get_contents('php://input');

        if (empty($query)) {
            $query = [];
        } else {
            $query = json_decode($query, true);
        }

        return $query;
    }

    /**
     * send a log message to the STDOUT stream.
     *
     * @param array<int, mixed> $args
     *
     * @return void
     */
    public static function log(...$args): void
    {
        foreach ($args as $arg) {
            if (is_object($arg) || is_array($arg) || is_resource($arg)) {
                $output = print_r($arg, true);
            } else {
                $output = (string) $arg;
            }

            fwrite(STDOUT, $output . "\n");
        }
    }
}
