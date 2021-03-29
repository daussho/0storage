<?php

declare(strict_types=1);

namespace App\Core;

use Rakit\Validation\Rule;
use SleekDB\Store;

class UniqueRule extends Rule
{
    protected $message = ":attribute :value has been used";

    protected $fillableParams = ['key', 'except'];

    protected $store;

    public function __construct(Store $store)
    {
        $this->store = $store;
    }

    public function check($value): bool
    {
        // make sure required parameters exists
        $this->requireParameters(['key']);

        // getting parameters
        $key = $this->parameter('key');
        $except = $this->parameter('except');

        if ($except and $except == $value) {
            return true;
        }

        // do query
        $filter = [$key, "=", $value];
        $data = $this->store->findBy($filter);

        // true for valid, false for invalid
        return empty($data);
    }
}
