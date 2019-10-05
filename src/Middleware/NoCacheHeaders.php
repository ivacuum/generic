<?php namespace Ivacuum\Generic\Middleware;

use Illuminate\Contracts\Foundation\Application;

class NoCacheHeaders
{
    /**
     * The application implementation.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;

    /**
     * Create a new middleware instance.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function handle($request, \Closure $next)
    {
        if ($this->app->environment('testing')) {
            return $next($request);
        }

        /** @var \Illuminate\Http\Response $response */
        $response = $next($request);

        if (method_exists($response, 'header')) {
            $response->header('Cache-Control', 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        }

        return $response;
    }
}
