<?php namespace Ivacuum\Generic\Controllers\Acp;

use App\ExternalIdentity as Model;
use Illuminate\Database\Eloquent\Builder;

class ExternalIdentities extends Controller
{
    public function index()
    {
        $user_id = request('user_id');
        $provider = request('provider');

        $models = Model::orderBy('id', 'desc')
            ->unless(is_null($user_id), function (Builder $query) use ($user_id) {
                return $query->where('user_id', $user_id);
            })
            ->when(is_null($user_id), function (Builder $query) {
                return $query->where('user_id', '<>', 0);
            })
            ->when($provider, function (Builder $query) use ($provider) {
                return $query->where('provider', $provider);
            })
            ->paginate()
            ->withPath(path("{$this->class}@index"));

        return view($this->view, compact('models'));
    }
}
