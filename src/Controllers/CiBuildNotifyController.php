<?php namespace Ivacuum\Generic\Controllers;

use Illuminate\Http\Request;
use Ivacuum\Generic\Services\Telegram;

class CiBuildNotifyController
{
    public function __invoke(Request $request, Telegram $telegram)
    {
        $emoji = '';
        $number = $request->input('build.number');
        $status = strtolower($request->input('build.status'));
        $project = $request->input('name');
        $statusText = '';

        if ($status === 'success') {
            $emoji = "\xE2\x9C\x85 ";
        } else {
            $statusText = " [{$status}]";
        }

        event(new \Ivacuum\Generic\Events\Stats\Build);

        $telegram->notifyAdmin("{$emoji}{$project} build {$number}{$statusText}");

        return 'ok';
    }
}
