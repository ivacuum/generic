<?php namespace Ivacuum\Generic\Exceptions;

abstract class LimitExceededException extends \Exception
{
    /**
     * @return string|\Illuminate\Support\HtmlString
     */
    abstract protected function message();

    /**
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        return back()
            ->with('message', $this->message())
            ->withInput();
    }
}
