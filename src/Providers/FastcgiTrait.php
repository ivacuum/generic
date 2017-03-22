<?php namespace Ivacuum\Generic\Providers;

trait FastcgiTrait
{
    protected function fastcgiFinishRequest()
    {
        if (function_exists('fastcgi_finish_request')) {
            register_shutdown_function('fastcgi_finish_request');
        }
    }
}
