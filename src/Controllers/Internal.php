<?php namespace Ivacuum\Generic\Controllers;

use App\Http\Controllers\Controller;
use Ivacuum\Generic\Services\Telegram;

class Internal extends Controller
{
    public function ciBuildNotifier(Telegram $telegram)
    {
        $emoji = '';
        $number = request()->input('build.number');
        $status = strtolower(request()->input('build.status'));
        $project = request()->input('name');
        $status_text = '';

        if ($status === 'success') {
            $emoji = "\xE2\x9C\x85 ";
        } else {
            $status_text = " [{$status}]";
        }

        event(new \Ivacuum\Generic\Events\Stats\Build);

        $telegram->notifyAdmin("{$emoji}{$project} build {$number}{$status_text}");

        return 'ok';
    }

    public function ip()
    {
        return request()->ip();
    }

    public function telegramWebhook()
    {
        \Log::info(json_encode(request()->all()));
    }
}
