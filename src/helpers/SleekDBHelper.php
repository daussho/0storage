<?php

namespace helpers;

class SleekDBHelper {
    static function insertParser(\SleekDB\Store $sleekDB, array $data)
    {
        $res = "";
        if (self::isAssoc($data)){
            $res = $sleekDB->insert($data);
        } else {
            $res = $sleekDB->insertMany($data);
        }
        return $res;
    }
    
    static function isAssoc(array $arr)
    {
        if (array() === $arr) return false;
        return array_keys($arr) !== range(0, count($arr) - 1);
    }
}