<?php

declare(strict_types=1);

namespace App\Helpers;

use App\Exceptions\ResponseException;
use Rakit\Validation\Validator;

/**
 * Global helper
 */
class GlobalHelper
{
    /**
     * @param array $rules
     * @param array $data
     *
     * @return void
     */
    public static function validateSchema(array $rules, array $data): void
    {
        $validator = new Validator();
        $validation = $validator->validate($data, $rules);

        if ($validation->fails()) {
            throw new ResponseException(400, "Error schema", $validation->errors()->toArray());
        }
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

    /**
     * @param array $arr
     *
     * @return bool
     */
    public static function isAssoc(array $arr): bool
    {
        if (array() === $arr) {
            return false;
        }

        return array_keys($arr) !== range(0, count($arr) - 1);
    }

    /**
     * @param mixed $data
     * 
     * @return void
     */
    public static function printExit($data): void
    {
        header("Content-Type: application/json");
        echo json_encode($data);
        exit;
    }

    /**
     * @param string $version
     * @param array $routes
     * 
     * @return array
     */
    public static function generateRoute(string $version, array $routes): array
    {
        foreach ($routes as $key => $value) {
            [$methods, $route, $action, $name] = $value;
            $routes[$key] = [
                $methods,
                "/$version$route",
                $action,
                "$name.$version",
            ];
        }

        return $routes;
    }

    public static function getAppVersion(): string
    {
        return $_ENV["APP_VERSION"] ?? "v0";
    }
}
