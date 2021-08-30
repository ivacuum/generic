<?php namespace Ivacuum\Generic\Listeners;

use Illuminate\Http\Request;
use Ivacuum\Generic\Services\Telegram;

/**
 * Заготовка для уведомлений в Телеграм
 */
class TelegramNotifier
{
    public function __construct(protected Request $request, protected Telegram $telegram)
    {
    }
}
