<?php

namespace Ivacuum\Generic\Controllers;

use App\Http\Controllers\Controller;

class Notifications extends Controller
{
    public function index()
    {
        /** @var \App\User $user */
        $user = request()->user();
        $notifications = $user->notifications;

        $user->markNotificationsAsRead();

        return view($this->view, ['notifications' => $notifications]);
    }
}
