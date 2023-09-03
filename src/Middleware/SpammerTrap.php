<?php

namespace Ivacuum\Generic\Middleware;

use Illuminate\Validation\ValidationException;

class SpammerTrap
{
    public function handle($request, \Closure $next)
    {
        /** @var \Illuminate\Http\Request $request */
        $method = $request->method();

        if (!in_array($method, ['POST', 'PUT'])) {
            return $next($request);
        }

        if ($method === 'POST' && $request->getProtocolVersion() === 'HTTP/1.0') {
            event(new \Ivacuum\Generic\Events\Stats\SpammerTrappedHttp1);

            throw ValidationException::withMessages([
                'mail' => [__('auth.spammer_trapped')],
            ]);
        }

        if (!$request->filled('mail')) {
            return $next($request);
        }

        event(new \Ivacuum\Generic\Events\Stats\SpammerTrapped);

        throw ValidationException::withMessages([
            'mail' => [__('auth.spammer_trapped')],
        ]);
    }
}
