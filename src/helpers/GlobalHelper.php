<?php

namespace helpers;

class GlobalHelper {
    static function validatePost($requiredFields, $post)
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

    static function returnJSON($data, $statusCode = 200)
    {
        header("Content-Type: application/json");
        http_response_code($statusCode);
        echo json_encode($data);
    }
}