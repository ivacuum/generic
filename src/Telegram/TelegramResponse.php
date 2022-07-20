<?php namespace Ivacuum\Generic\Telegram;

use Illuminate\Http\Client\Factory;
use Illuminate\Http\Client\Response;

class TelegramResponse
{
    public readonly bool $successful;

    public function __construct(Response $response)
    {
        $this->successful = $response->json('ok');
    }

    public static function fakeSuccess()
    {
        return [
            'api.telegram.org/*' => Factory::response([
                'ok' => true,
            ]),
        ];
    }
}
