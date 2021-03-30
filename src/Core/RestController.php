<?php

declare(strict_types=1);

namespace App\Core;

use App\Helpers\GlobalHelper;
use App\Helpers\ResponseHelper;
use Exception;

class RestController
{
    /**
     * @var array
     */
    private $_exclude;

    /**
     * @param string|null $key
     *
     * @return mixed
     */
    protected function getQuery(string $key = null)
    {
        $query = file_get_contents('php://input');

        if (empty($query)) {
            throw new Exception("Empty body");
        }

        $query = json_decode($query, true);

        return empty($key) ? $query : $query[$key];
    }

    /**
     * @param array $data
     * @param int $statusCode
     *
     * @return void
     */
    protected function returnJSON(array $data, $statusCode = 200): void
    {
        if (!empty($this->_exclude)) {
            foreach ($this->_exclude as $key => $value) {
                unset($data[$value]);
            }
        }

        ResponseHelper::returnJSON([
            "code" => $statusCode,
            "success" => $statusCode === 200,
            "data" => $data,
            "timestamp" => date(DATE_ISO8601),
        ], $statusCode);
    }

    protected function exclude(array $exclude)
    {
        $this->_exclude = $exclude;
        return $this;
    }
}
