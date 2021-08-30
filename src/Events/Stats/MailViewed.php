<?php namespace Ivacuum\Generic\Events\Stats;

use Ivacuum\Generic\Events\Event;

class MailViewed extends Event
{
    public $table = 'emails';

    public function __construct(public $id)
    {
    }
}
