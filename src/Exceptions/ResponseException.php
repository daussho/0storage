<?php

namespace App\Exceptions;

class ResponseException extends \Exception
{
    private $errData;
    private $errCode;

    public function __construct(int $errCode, string $err, array $arg = [])
    {
        parent::__construct($err);
        $this->errData = $arg;
        $this->errCode = $errCode;
    }

    public function errorData()
    {
        return $this->errData;
    }

    public function errorCode()
    {
        return $this->errCode;
    }
}
