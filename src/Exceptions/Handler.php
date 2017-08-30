<?php namespace Ivacuum\Generic\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Ivacuum\Generic\Utilities\ExceptionHelper;
use Laravel\Socialite\Two\InvalidStateException;

class Handler extends ExceptionHandler
{
    protected $dontReport = [
        InvalidStateException::class,
    ];

    protected $report_validation_exception = true;

    /**
     * @param  \Exception $e
     * @return void
     */
    public function report(\Exception $e)
    {
        $this->reportValidationException($e);

        if ($this->shouldReport($e) && false === config('app.debug', false)) {
            $this->reportTelegram($e);
        }

        parent::report($e);
    }

    /**
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception $e
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function render($request, \Exception $e)
    {
        abort_if($e instanceof ModelNotFoundException, 404);

        if ($e instanceof TokenMismatchException) {
            return back()->withInput()
                ->with('message', 'Пожалуйста, повторите отправку формы. За два часа мы вас подзабыли');
        }

        return parent::render($request, $e);
    }

    protected function convertExceptionToResponse(\Exception $e)
    {
        if (config('app.debug', false)) {
            return parent::convertExceptionToResponse($e);
        }

        return response()->view('errors.500', ['exception' => $e], 500);
    }

    protected function reportTelegram(\Exception $e)
    {
        ExceptionHelper::log($e);

        if ($previous = $e->getPrevious()) {
            $this->reportTelegram($previous);
        }
    }

    protected function reportValidationException(\Exception $e)
    {
        if ($e instanceof ValidationException
            && $this->report_validation_exception
            && false === config('app.debug', false)
        ) {
            ExceptionHelper::logValidation($e);
        }
    }

    /**
     * @param  \Illuminate\Http\Request $request
     * @param  \Illuminate\Auth\AuthenticationException $e
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $e)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        return redirect()->guest(path('Auth@login'))
            ->with('message', trans('auth.signin_to_view_page'));
    }
}
