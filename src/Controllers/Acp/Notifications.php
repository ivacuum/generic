<?php namespace Ivacuum\Generic\Controllers\Acp;

use App\Notification as Model;

class Notifications extends Controller
{
    public function index()
    {
        $models = Model::orderBy('created_at', 'desc')->paginate();

        return view($this->view, compact('models'));
    }
}
