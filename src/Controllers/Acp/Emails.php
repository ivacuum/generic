<?php namespace Ivacuum\Generic\Controllers\Acp;

use App\Email as Model;
use Illuminate\Database\Eloquent\Builder;

class Emails extends Controller
{
    protected $sortableKeys = ['id', 'clicks', 'views'];

    public function index()
    {
        $userId = request('user_id');
        $template = request('template');

        [$sortKey, $sortDir] = $this->getSortParams();

        $models = Model::orderBy($sortKey, $sortDir)
            ->when($userId, fn (Builder $query) => $query->where('user_id', $userId))
            ->when($template, fn (Builder $query) => $query->where('template', $template))
            ->paginate();

        return view($this->view, [
            'models' => $models,
            'template' => $template,
        ]);
    }
}
