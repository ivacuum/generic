<?php namespace Ivacuum\Generic\Http;

interface HttpRequest extends \JsonSerializable
{
    public function endpoint(): string;
}
