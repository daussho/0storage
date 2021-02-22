<?php

namespace helpers;

class GlobalHelper {
    public static function validateSchema($requiredFields, $post)
    {
        $validation = [];

        foreach($requiredFields as $required => $key)
        {
            if(!array_key_exists($key, $post))
            {
                $validation['required'][] = $key;
            }
        }

        return $validation;
    }

    public static function returnJSON($data, $statusCode = 200)
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
    public static function log(...$args): void {
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