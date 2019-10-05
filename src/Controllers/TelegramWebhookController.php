<?php namespace Ivacuum\Generic\Controllers;

use Illuminate\Http\Request;
use Illuminate\Log\Logger;

class TelegramWebhookController
{
    public function __invoke(Logger $logger, Request $request)
    {
        $logger->info(json_encode($request->all()));
    }
}
