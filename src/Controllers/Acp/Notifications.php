<?php namespace Ivacuum\Generic\Controllers\Acp;

use App\Notification as Model;

class Notifications extends Controller
{
    public function index()
    {
        $models = Model::orderByDesc('created_at')
            ->paginate();

        return view($this->view, ['models' => $models]);
    }
}
