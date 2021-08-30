<?php namespace Ivacuum\Generic\Events;

use App\ExternalIdentity;
use App\User;

class ExternalIdentityFirstLogin extends Event
{
    public function __construct(public ExternalIdentity $identity, public User $user)
    {
    }
}
