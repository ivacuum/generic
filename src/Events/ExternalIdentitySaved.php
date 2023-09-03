<?php

namespace Ivacuum\Generic\Events;

use Laravel\Socialite\AbstractUser;

class ExternalIdentitySaved extends Event
{
    public function __construct(public AbstractUser $user)
    {
    }
}
