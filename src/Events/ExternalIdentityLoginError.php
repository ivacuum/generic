<?php

namespace Ivacuum\Generic\Events;

use Illuminate\Http\Request;

class ExternalIdentityLoginError extends Event
{
    public function __construct(public string $provider, public Request $request)
    {
    }
}
