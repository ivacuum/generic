<?php

namespace Ivacuum\Generic\Events;

/**
 * Превышение лимита создания записей
 */
class LimitExceeded extends Event
{
    public function __construct(public string $title, public string $value)
    {
    }
}
