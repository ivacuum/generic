<?php namespace Ivacuum\Generic\Utilities;

use Illuminate\Validation\ValidationException;
use Ivacuum\Generic\Services\Telegram;

class ExceptionHelper
{
    public static function log(\Exception $e): void
    {
        app(Telegram::class)->notifyAdmin(static::summary($e));
    }

    public static function isSpammerTrapped(ValidationException $e): bool
    {
        if (isset($e->validator->failed()['mail']['Empty'])) {
            return true;
        }

        if ($e->validator->errors()->first('mail')) {
            return true;
        }

        return false;
    }

    public static function logValidation(ValidationException $e): bool
    {
        if (static::isSpammerTrapped($e)) {
            return false;
        }

        app(Telegram::class)->notifyAdmin(static::validationSummary($e));

        return true;
    }

    /**
     * Нормализация информации об исключении
     *
     * @param  \Exception $e
     * @return array
     */
    public static function normalize(\Exception $e): array
    {
        return [
            'class'   => get_class($e),
            'message' => $e->getMessage(),
            'code'    => $e->getCode(),
            'file'    => "{$e->getFile()}:{$e->getLine()}",
        ];
    }

    /**
     * Короткое сообщение для лога
     *
     * Пример:
     * ErrorException (code: 1)
     * Maximum execution time of 30 seconds exceeded
     * /public/index.php:124
     *
     * @param  \Exception $e
     * @return string
     */
    public static function summary(\Exception $e): string
    {
        $data = static::normalize($e);

        return sprintf(
            "%s\n%s%s\n%s\n%s",
            str_replace('App\Http\Controllers\\', '', \Route::currentRouteAction()),
            $data['class'],
            $data['code'] ? " (code: {$data['code']})" : '',
            $data['message'],
            $data['file']
        );
    }

    /**
     * Информация об ошибке валидации
     *
     * @param  \Illuminate\Validation\ValidationException $e
     * @return string
     */
    public static function validationSummary(ValidationException $e): string
    {
        $text = "Ошибка валидации ".fullUrl()."\n";
        $text .= json_encode([
            'validator' => $e->validator->failed(),
            'request' => \Request::all(),
            'browser' => UserAgent::tidy(\Request::userAgent()),
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        return $text;
    }
}
