<?php

declare(strict_types=1);

namespace App\Core;

use App\Exceptions\ResponseException;
use App\Helpers\SleekDBHelper;
use Rakit\Validation\Validator;
use SleekDB\Store;

class Model extends Store
{
    /**
     * @var array
     */
    protected $_rules;

    public function __construct($storeName, $rules = [])
    {
        parent::__construct(
            $storeName,
            SleekDBHelper::getAppDir(),
            SleekDBHelper::getDBConfig()
        );
        $this->_rules = $rules;
    }

    public function insertStrict($data)
    {
        $this->validateModel($data);

        return $this->insert($data);
    }

    protected function validateModel($data)
    {
        $validator = new Validator();

        $validator->addValidator('unique', new UniqueRule($this));

        $validation = $validator->validate($data, $this->_rules);

        if ($validation->fails()) {
            throw new ResponseException("Error schema", $validation->errors()->toArray(), 400);
        }
    }
}
