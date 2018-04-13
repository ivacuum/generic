<?php namespace Ivacuum\Generic\Controllers\Acp;

use App\Email as Model;
use Illuminate\Database\Eloquent\Builder;

class Emails extends Controller
{
    protected $sortable_keys = ['id', 'clicks', 'views'];

    public function index()
    {
        $user_id = request('user_id');
        $template = request('template');

        [$sort_key, $sort_dir] = $this->getSortParams();

        $models = Model::orderBy($sort_key, $sort_dir)
            ->when($user_id, function (Builder $query) use ($user_id) {
                return $query->where('user_id', $user_id);
            })
            ->when($template, function (Builder $query) use ($template) {
                return $query->where('template', $template);
            })
            ->paginate()
            ->withPath(path("{$this->class}@index"));

        return view($this->view, compact('models', 'template'));
    }
}
