<?php

namespace Ivacuum\Generic\Rules;

use Illuminate\Contracts\Validation\Rule;

class ConcurrencyControl implements Rule
{
    const FIELD = '_concurrency_control';

    protected $key;

    public function __construct($key)
    {
        $this->key = md5($key);
    }

    public function passes($attribute, $value): bool
    {
        return $this->key === $value;
    }

    public function message(): string
    {
        return __('validation.concurrency_control');
    }
}
