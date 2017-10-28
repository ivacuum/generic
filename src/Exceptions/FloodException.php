<?php namespace Ivacuum\Generic\Exceptions;

class FloodException extends \Exception
{
    /**
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        return back()
            ->with('message', trans('limits.flood_control'))
            ->withInput();
    }
}
