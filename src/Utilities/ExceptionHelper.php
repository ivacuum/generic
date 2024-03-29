<?php

namespace Ivacuum\Generic\Utilities;

use Illuminate\Validation\ValidationException;
use Ivacuum\Generic\Services\Telegram;

class ExceptionHelper
{
    public static function log(\Throwable $e): void
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

    public static function normalize(\Throwable $e): array
    {
        return [
            'class' => get_class($e),
            'message' => mb_substr($e->getMessage(), 0, 3000),
            'code' => $e->getCode(),
            'file' => "{$e->getFile()}:{$e->getLine()}",
        ];
    }

    public static function summary(\Throwable $e): string
    {
        $data = static::normalize($e);

        /**
         * Короткое сообщение для лога
         *
         * Пример:
         * ErrorException (code: 1)
         * Maximum execution time of 30 seconds exceeded
         * /public/index.php:124
         */
        return sprintf(
            "%s\n%s%s\n%s\n%s\n%s",
            str_replace('App\Http\Controllers\\', '', \Route::currentRouteAction()),
            $data['class'],
            $data['code'] ? " (code: {$data['code']})" : '',
            $data['message'],
            $data['file'],
            fullUrl()
        );
    }

    public static function validationSummary(ValidationException $e): string
    {
        $text = 'Ошибка валидации ' . fullUrl() . "\n";
        $text .= json_encode([
            'validator' => $e->validator->failed(),
            'request' => \Request::all(),
            'browser' => UserAgent::tidy(\Request::userAgent()),
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        return $text;
    }
}
