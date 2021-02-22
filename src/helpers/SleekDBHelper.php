<?php

namespace helpers;

use SleekDB\Store;

class SleekDBHelper {
    private const MAX_QUERY_CACHE = 60 * 60;
    private const DATA_DIR = __DIR__ . "/../../mydb";
    private const DB_CONFIG = [
        "auto_cache" => true,
        "cache_lifetime" => self::MAX_QUERY_CACHE,
        "timeout" => 120,
        "primary_key" => "_id"
    ];

    private static function getTableName($appName, $tableName)
    {
        return hash("crc32", $appName) . "_" . $appName. "_" . $tableName;
    }

    private static function getStore($query)
    {
        return new Store(self::getTableName($query['app_name'], $query['table']), self::DATA_DIR, self::DB_CONFIG);
    }
    
    public static function insertParser(array $param)
    {
        $store = self::getStore($param);
        $res = "";
        if (self::isAssoc($param['data'])){
            $res = $store->insert($param['data']);
        } else {
            $res = $store->insertMany($param['data']);
        }
        return $res;
    }

    public static function queryBuilder($param)
    {
        $store = self::getStore($param);
        $builder = $store->createQueryBuilder();

        if (!empty($param['select']) && is_array($param['select'])){
            $builder = $builder->select($param['select']);
        }

        if (!empty($param['join'])){
            $joinStore = new Store(
                self::getTableName($param['app_name'], $param['join']['table']),
                self::DATA_DIR, 
                self::DB_CONFIG
            );

            $builder = $builder->join(
                function($var) use ($joinStore, $param){
                    return $joinStore->findBy([ 
                        $param['join']['foreign_key'], 
                        "===", 
                        $var[$param['join']['relation_key']]
                    ]);
                }, 
                $param['join']['table']
            );
        }

        if (!empty($param['where'])){
            $builder = $builder->where([$param['where']]);
        }

        if (!empty($param['search'])){
            $builder = $builder->search($param['search']['fields'], $param['search']['keyword']);
        }

        if (!empty($param['distinct'])){
            $builder = $builder->distinct($param['distinct']);
        }
        
        if (!empty($param['skip'])){
            $builder = $builder->skip($param['skip']);
        }

        if (!empty($param['order_by'])){
            $builder = $builder->orderBy($param['order_by']);
        }

        $joinStore = new Store(
            self::getTableName($param['app_name'], $param['join']['table']),
            self::DATA_DIR, 
            self::DB_CONFIG
        );

        $data = $builder
            ->disableCache()
            ->getQuery()
            ->fetch();
        
        return $data;
    }

    public static function update($param)
    {
        $store = self::getStore($param);
        if ($param['update'] == "update"){
            return ["success" => $store->update($param['data'])];
        } else if ($param['update'] == "update_by_id") {
            return $store->updateById($param['id'], $param['data']);
        }
    }
    
    static function isAssoc(array $arr)
    {
        if (array() === $arr) return false;
        return array_keys($arr) !== range(0, count($arr) - 1);
    }
}