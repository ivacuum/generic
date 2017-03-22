<?php namespace Ivacuum\Generic\Controllers;

use App\Http\Controllers\Controller;

class Notifications extends Controller
{
    public function index()
    {
        $user = $this->request->user();
        $notifications = $user->notifications;

        $user->markNotificationsAsRead();

        return view($this->view, compact('notifications'));
    }
}
