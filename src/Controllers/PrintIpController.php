<?php namespace Ivacuum\Generic\Controllers;

use Illuminate\Http\Request;

class PrintIpController
{
    public function __invoke(Request $request)
    {
        return $request->ip();
    }
}
