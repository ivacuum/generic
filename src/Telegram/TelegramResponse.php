<?php namespace Ivacuum\Generic\Telegram;

use Illuminate\Http\Client\Factory;
use Illuminate\Http\Client\Response;

class TelegramResponse
{
    public readonly bool $successful;
    public readonly int|null $messageId;

    public function __construct(Response $response)
    {
        $this->messageId = $response->json('result.message_id');
        $this->successful = $response->json('ok');
    }

    public static function fakeBlockedByUser()
    {
        return [
            'api.telegram.org/*' => Factory::response([
                'ok' => false,
                'error_code' => 403,
                'description' => 'Forbidden: bot was blocked by the user',
            ]),
        ];
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
