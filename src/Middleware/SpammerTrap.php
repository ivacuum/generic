<?php namespace Ivacuum\Generic\Middleware;

use Illuminate\Validation\ValidationException;

class SpammerTrap
{
    public function handle($request, \Closure $next)
    {
        /* @var \Illuminate\Http\Request $request */
        if (!in_array($request->method(), ['POST', 'PUT'])) {
            return $next($request);
        }

        if (!$request->filled('mail')) {
            return $next($request);
        }

        event(new \Ivacuum\Generic\Events\Stats\SpammerTrapped);

        throw ValidationException::withMessages([
            'mail' => [trans('auth.spammer_trapped')],
        ]);
    }
}
