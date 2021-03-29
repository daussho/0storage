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

    /**
     * Creates a new object in the store.
     * It is stored as a plaintext JSON document.
     * @param array $data
     * @return array
     * @throws IOException
     * @throws IdNotAllowedException
     * @throws InvalidArgumentException
     * @throws JsonException
     * @throws ResponseException
     */
    public function insert(array $data): array
    {
        $this->validateModel($data);

        return parent::insert($data);
    }

    /**
     * Creates multiple objects in the store.
     * @param array $data
     * @return array
     * @throws IOException
     * @throws IdNotAllowedException
     * @throws InvalidArgumentException
     * @throws JsonException
     * @throws ResponseException
     */

    public function insertMany(array $data): array
    {
        foreach ($data as $key => $value) {
            $this->validateModel($value);
        }

        return parent::insertMany($data);
    }

    /**
     * Validate data based on rules
     * @param array $data
     * @return void
     * @throws ResponseException
     */
    protected function validateModel(array $data): void
    {
        $validator = new Validator();

        $validator->addValidator('unique', new UniqueRule($this));

        $validation = $validator->validate($data, $this->_rules);

        if ($validation->fails()) {
            throw new ResponseException("Error schema", $validation->errors()->toArray(), 400);
        }
    }
}
