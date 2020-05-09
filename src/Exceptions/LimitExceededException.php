<?php namespace Ivacuum\Generic\Exceptions;

abstract class LimitExceededException extends \Exception
{
    /**
     * @return string|\Illuminate\Support\HtmlString
     */
    abstract protected function message();

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        if ($request->expectsJson()) {
            return response(['message' => $this->message()], 429);
        }

        return back()
            ->with('message', $this->message())
            ->withInput();
    }
}
