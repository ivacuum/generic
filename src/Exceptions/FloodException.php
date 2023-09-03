<?php

namespace Ivacuum\Generic\Exceptions;

class FloodException extends \Exception
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        if ($request->expectsJson()) {
            return response(['message' => __('limits.flood_control')], 429);
        }

        return back()
            ->with('message', __('limits.flood_control'))
            ->withInput();
    }
}
