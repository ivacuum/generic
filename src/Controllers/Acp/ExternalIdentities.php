<?php namespace Ivacuum\Generic\Controllers\Acp;

use App\ExternalIdentity as Model;

class ExternalIdentities extends Controller
{
    public function index()
    {
        $models = Model::orderBy('id', 'desc')->paginate();

        return view($this->view, compact('models'));
    }
}
