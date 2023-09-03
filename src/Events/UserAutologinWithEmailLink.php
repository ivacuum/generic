<?php

namespace Ivacuum\Generic\Events;

use App\Email;
use Illuminate\Queue\SerializesModels;

/**
 * Автовход по ссылке из письма
 */
class UserAutologinWithEmailLink extends Event
{
    use SerializesModels;

    public function __construct(public Email $email)
    {
    }
}
