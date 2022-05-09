<?php namespace Ivacuum\Generic\Controllers\Acp;

use App\ExternalIdentity as Model;
use Illuminate\Database\Eloquent\Builder;

class ExternalIdentities extends Controller
{
    public function index()
    {
        $userId = request('user_id');
        $provider = request('provider');

        $models = Model::orderByDesc('id')
            ->with('user')
            ->unless(null === $userId, fn (Builder $query) => $query->where('user_id', $userId))
            ->when(null === $userId, fn (Builder $query) => $query->where('user_id', '<>', 0))
            ->when($provider, fn (Builder $query) => $query->where('provider', $provider))
            ->paginate();

        return view($this->view, ['models' => $models]);
    }
}
