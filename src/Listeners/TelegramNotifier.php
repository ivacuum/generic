<?php namespace Ivacuum\Generic\Listeners;

use Illuminate\Http\Request;
use Ivacuum\Generic\Services\Telegram;

/**
 * Заготовка для уведомлений в Телеграм
 */
class TelegramNotifier
{
    protected $request;
    protected $telegram;

    public function __construct(Request $request, Telegram $telegram)
    {
        $this->request = $request;
        $this->telegram = $telegram;
    }
}
