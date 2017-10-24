<?php namespace Ivacuum\Generic\Events;

/**
 * Превышение лимита создания записей
 */
class LimitExceeded extends Event
{
    public $title;
    public $value;

    public function __construct(string $title, string $value)
    {
        $this->title = $title;
        $this->value = $value;
    }
}
