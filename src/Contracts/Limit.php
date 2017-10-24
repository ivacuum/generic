<?php namespace Ivacuum\Generic\Contracts;

interface Limit
{
    public function ipExceeded(): bool;
    public function userExceeded(): bool;
}
