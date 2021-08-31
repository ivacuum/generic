<?php namespace Ivacuum\Generic\Telegram;

interface RequestInterface extends \JsonSerializable
{
    public function endpoint(): string;
}
