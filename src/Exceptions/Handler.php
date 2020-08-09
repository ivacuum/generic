<?php namespace Ivacuum\Generic\Exceptions;

use App\Http\Controllers\Auth\SignIn;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
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

    protected $reportValidationException = true;

    public function report(\Throwable $e)
    {
        if ($this->isDatabaseOffline($e)) {
            return;
        }

        $this->reportValidationException($e);

        if ($this->shouldReport($e) && false === config('app.debug', false)) {
            $this->reportTelegram($e);
        }

        parent::report($e);
    }

    public function render($request, \Throwable $e)
    {
        abort_if($e instanceof ModelNotFoundException, 404);

        if ($e instanceof TokenMismatchException) {
            return back()->withInput()
                ->with('message', 'Пожалуйста, повторите отправку формы. За два часа мы вас подзабыли');
        }

        return parent::render($request, $e);
    }

    protected function convertExceptionToResponse(\Throwable $e)
    {
        if (config('app.debug', false)) {
            return parent::convertExceptionToResponse($e);
        }

        return response()->view('errors.500', ['exception' => $e], 500);
    }

    protected function isDatabaseOffline(\Throwable $e): bool
    {
        if ($e instanceof QueryException && $e->getCode() === 2002) {
            return true;
        }

        // QueryException может быть не на верхнем уровне, а, например, на третьем
        if ($previous = $e->getPrevious()) {
            return $this->isDatabaseOffline($previous);
        }

        return false;
    }

    protected function shouldReportValidationException(): bool
    {
        return $this->reportValidationException
            && false === config('app.debug', false);
    }

    protected function reportTelegram(\Throwable $e): void
    {
        ExceptionHelper::log($e);

        if ($previous = $e->getPrevious()) {
            $this->reportTelegram($previous);
        }
    }

    protected function reportValidationException(\Throwable $e): void
    {
        if ($e instanceof ValidationException && $this->shouldReportValidationException()) {
            ExceptionHelper::logValidation($e);
        }
    }

    protected function unauthenticated($request, AuthenticationException $e)
    {
        return $request->expectsJson()
            ? response()->json(['message' => 'Unauthenticated.'], 401)
            : redirect()->guest(path([SignIn::class, 'index']))
                ->with('message', __('auth.signin_to_view_page'));
    }
}
