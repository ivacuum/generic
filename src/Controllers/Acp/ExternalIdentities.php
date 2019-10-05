<?php namespace Ivacuum\Generic\Controllers\Acp;

use App\ExternalIdentity as Model;
use Illuminate\Database\Eloquent\Builder;

class ExternalIdentities extends Controller
{
    public function index()
    {
        $userId = request('user_id');
        $provider = request('provider');

        $models = Model::orderBy('id', 'desc')
            ->unless(null === $userId, function (Builder $query) use ($userId) {
                return $query->where('user_id', $userId);
            })
            ->when(null === $userId, function (Builder $query) {
                return $query->where('user_id', '<>', 0);
            })
            ->when($provider, function (Builder $query) use ($provider) {
                return $query->where('provider', $provider);
            })
            ->paginate()
            ->withPath(path("{$this->class}@index"));

        return view($this->view, ['models' => $models]);
    }
}
