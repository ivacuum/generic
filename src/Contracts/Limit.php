<?php namespace Ivacuum\Generic\Contracts;

interface Limit
{
    public function floodControl(): bool;
    public function ipExceeded(): bool;
    public function userExceeded(): bool;
}
