<?php

namespace helpers;

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

        return $validation;
    }

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
