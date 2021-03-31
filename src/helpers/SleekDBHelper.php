<?php

namespace App\Helpers;

use SleekDB\Store;

/**
 * Helper for SleekDB query
 */
class SleekDBHelper
{
    private const MAX_QUERY_CACHE = 30;
    private const DATA_DIR = __DIR__ . "/../../mydb";
    private const DB_CONFIG = [
        "auto_cache" => false,
        "cache_lifetime" => self::MAX_QUERY_CACHE,
        "timeout" => 120,
        "primary_key" => "_id",
    ];

    /**
     * @return string
     */
    public static function getAppDir(): string
    {
        return self::DATA_DIR . "/" . hash($_ENV['DB_HASH'], $_ENV['APP_NAME']) . "_" . $_ENV['APP_NAME'];
    }

    /**
     * @param array $query
     *
     * @return Store
     */
    public static function getStore(string $table): Store
    {
        return new Store(
            $table,
            self::getAppDir($_ENV['APP_NAME']),
            self::DB_CONFIG
        );
    }

    /**
     * @return array
     */
    public static function getDBConfig(): array
    {
        return self::DB_CONFIG;
    }
}
