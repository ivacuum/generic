<?php

namespace Ivacuum\Generic\Events;

use App\ExternalIdentity;

class ExternalIdentityLogin extends Event
{
    public function __construct(public ExternalIdentity $identity)
    {
    }
}
