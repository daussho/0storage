<?php

namespace helpers;

use SleekDB\Store;

class SleekDBHelper {
    const MAX_QUERY_CACHE = 60 * 60;

    static function insertParser(Store $sleekDB, array $data)
    {
        $res = "";
        if (self::isAssoc($data)){
            $res = $sleekDB->insert($data);
        } else {
            $res = $sleekDB->insertMany($data);
        }
        return $res;
    }

    static function queryBuilder(Store $store, $param)
    {
        $builder = $store->createQueryBuilder();

        if (!empty($param['select']) && is_array($param['select'])){
            $builder = $builder->select($param['select']);
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

        $data = $builder
            ->useCache(self::MAX_QUERY_CACHE)
            ->getQuery()
            ->fetch();
        
        return $data;
    }
    
    static function isAssoc(array $arr)
    {
        if (array() === $arr) return false;
        return array_keys($arr) !== range(0, count($arr) - 1);
    }
}