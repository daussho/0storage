<?php

namespace App\Controllers;

class RestController
{
    protected function getQuery()
    {
        $query = file_get_contents('php://input');

        if (empty($query)) {
            $query = [];
        } else {
            $query = json_decode($query, true);
        }

        return $query;
    }
}
