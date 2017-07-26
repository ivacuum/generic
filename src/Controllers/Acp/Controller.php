<?php namespace Ivacuum\Generic\Controllers\Acp;

use Illuminate\Database\Eloquent\Builder;

class Controller extends BaseController
{
    protected $sort_dir = 'desc';
    protected $sort_key = 'id';
    protected $sortable_keys = ['id'];
    protected $show_with_count;

    public function create()
    {
        $model = $this->createGeneric();

        return view($this->getView(), compact('model'));
    }

    public function createGeneric()
    {
        $model = $this->newModel();

        $this->authorize('create', $model);

        \Breadcrumbs::push(trans($this->view));

        return $model;
    }

    public function destroy($id)
    {
        $model = $this->destroyGeneric($id);

        $this->destroyModel($model);

        return $this->redirectAfterDestroy();
    }

    public function destroyGeneric($id)
    {
        $model = $this->getModel($id);

        $this->authorize('destroy', $model);

        return $model;
    }

    public function edit($id)
    {
        $model = $this->editGeneric($id);

        return view($this->getView(), compact('model'));
    }

    public function editGeneric($id)
    {
        $model = $this->getModel($id);

        $this->authorize('edit', $model);
        $this->breadcrumbsModelSubpage($model);

        return $model;
    }

    public function indexBefore()
    {
        $model = $this->newModel();

        $this->authorize('list', $model);

        $model_tpl = implode('.', array_map(function ($ary) {
            return str_replace('_', '-', snake_case($ary));
        }, explode('\\', str_replace('App\\', '', get_class($model)))));

        list($sort_key, $sort_dir) = $this->getSortParams();

        \UrlHelper::setSortKey($sort_key)
            ->setDefaultSortDir($this->sort_dir);

        $q = $this->request->input('q');

        view()->share(compact('model', 'model_tpl', 'q', 'sort_dir', 'sort_key'));
    }

    public function show($id)
    {
        $model = $this->showGeneric($id);

        return view($this->getView(), compact('model'));
    }

    public function showGeneric($id)
    {
        $model = $this->getModel($id);

        $this->authorize('show', $model);

        \Breadcrumbs::push($model->breadcrumb());

        view()->share(['meta_title' => $model->breadcrumb()]);

        return $model;
    }

    public function store()
    {
        $this->storeGeneric();

        $model = $this->storeModel();

        return $this->redirectAfterStore($model);
    }

    public function storeGeneric()
    {
        $this->authorize('create', $this->newModel());
        $this->sanitizeRequest();
        $this->validate($this->request, $this->rules());
    }

    public function update($id)
    {
        $model = $this->updateGeneric($id);

        $this->updateModel($model);

        return $this->redirectAfterUpdate($model);
    }

    public function updateGeneric($id)
    {
        $model = $this->getModel($id);

        $this->authorize('edit', $model);
        $this->sanitizeRequest();
        $this->validate($this->request, $this->rules($model));

        return $model;
    }

    protected function appendViewSharedVars()
    {
        parent::appendViewSharedVars();

        view()->share([
            'show_with_count' => $this->show_with_count,
        ]);
    }

    protected function sanitizeData(array $data)
    {
        return;
    }

    protected function sanitizeRequest()
    {
        $data = $this->request->all();

        if (is_array($sanitized_data = $this->sanitizeData($data))) {
            $this->request->replace($sanitized_data);
        }
    }

    /**
     * @param \Ivacuum\Generic\Models\Model|\Eloquent $model
     */
    protected function breadcrumbsModelSubpage($model)
    {
        \Breadcrumbs::push(
            $model->breadcrumb(),
            str_replace('.', '/', $this->prefix)."/{$model->getRouteKey()}"
        );

        \Breadcrumbs::push(trans($this->view));
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     */
    protected function destroyModel($model)
    {
        $model->delete();
    }

    /**
     * @param  integer $id
     * @return \Ivacuum\Generic\Models\Model
     */
    protected function getModel($id)
    {
        $model = $this->newModel();

        return $model->where($model->getRouteKeyName(), '=', $id)
            ->when(method_exists($model, 'bootSoftDeletes'), function (Builder $query) {
                return $query->withTrashed();
            })
            ->when($this->method === 'show' && !is_null($this->show_with_count), function (Builder $query) {
                return $query->withCount($this->show_with_count);
            })
            ->firstOrFail();
    }

    protected function getModelName()
    {
        return str_singular(str_replace('Acp\\', 'App\\', $this->class));
    }

    protected function getSortParams()
    {
        $sort_dir = $this->request->input('sd', $this->sort_dir);
        $sort_key = $this->request->input('sk', $this->sort_key);

        if (!in_array($sort_dir, ['asc', 'desc'])) {
            $sort_dir = $this->sort_dir;
        }

        if (!in_array($sort_key, $this->sortable_keys)) {
            $sort_key = $this->sort_key;
        }

        return [$sort_key, $sort_dir];
    }

    protected function getView()
    {
        return view()->exists($this->view) ? $this->view : "acp.{$this->method}";
    }

    /**
     * @return \Illuminate\Database\Eloquent\Model
     */
    protected function newModel()
    {
        $model = $this->getModelName();

        return new $model;
    }

    protected function redirectAfterDestroy()
    {
        return [
            'status' => 'OK',
            'redirect' => path("{$this->class}@index"),
        ];
    }

    protected function redirectAfterStore($model)
    {
        return redirect(path("{$this->class}@index"));
    }

    protected function redirectAfterUpdate($model, $method = 'index')
    {
        $goto = $this->request->input('goto', '');

        if ($this->request->exists('_save')) {
            return $goto
                ? redirect(path("{$this->class}@edit", [$model, 'goto' => $goto]))
                : redirect(path("{$this->class}@edit", $model));
        }

        return $goto ? redirect($goto) : redirect(path("{$this->class}@{$method}"));
    }

    protected function rules($model = null)
    {
        return [];
    }

    protected function storeModel()
    {
        $model = $this->newModel()->fill($this->request->all());
        $model->save();

        return $model;
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     */
    protected function updateModel($model)
    {
        $model->update($this->request->all());
    }
}
