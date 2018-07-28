<?php namespace Ivacuum\Generic\Controllers\Acp;

use Illuminate\Database\Eloquent\Builder;
use Ivacuum\Generic\Rules\ConcurrencyControl;
use Ivacuum\Generic\Utilities\ModelHelper;
use Ivacuum\Generic\Utilities\NamingHelper;

class Controller extends BaseController
{
    protected $api_only = false;
    protected $sort_dir = 'desc';
    protected $sort_key = 'id';
    protected $sortable_keys = ['id'];
    protected $show_with_count;

    public function create()
    {
        if ($this->api_only && !$this->isApiRequest()) {
            return $this->apiOnlyResponse();
        }

        $model = $this->createGeneric();

        if (!$this->isApiRequest()) {
            return view($this->getView(), compact('model'));
        }

        return array_merge(
            ['model' => $model],
            ['breadcrumbs' => \Breadcrumbs::get()],
            $this->appendToCreateAndEditResponse($model)
        );
    }

    public function createGeneric()
    {
        $model = $this->newModel();

        $this->authorize('create', $model);

        \Breadcrumbs::push(trans($this->view));

        return $this->newModelDefaults($model);
    }

    public function destroy($id)
    {
        $model = $this->destroyGeneric($id);

        $this->destroyModel($model);

        return $this->redirectAfterDestroy($model);
    }

    public function destroyGeneric($id)
    {
        $model = $this->getModel($id);

        $this->authorize('destroy', $model);

        return $model;
    }

    public function edit($id)
    {
        if ($this->api_only && !$this->isApiRequest()) {
            return $this->apiOnlyResponse();
        }

        $model = $this->editGeneric($id);

        if (!$this->isApiRequest()) {
            return view($this->getView(), compact('model'));
        }

        return array_merge(
            ['model' => $model],
            ['breadcrumbs' => \Breadcrumbs::get()],
            $this->appendToCreateAndEditResponse($model)
        );
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
        if ($this->api_only && !$this->isApiRequest()) {
            return $this->apiOnlyResponse();
        }

        $model = $this->newModel();

        $this->authorize('list', $model);

        $model_tpl = implode('.', array_map(function ($ary) {
            return snake_case($ary, '-');
        }, explode('\\', str_replace('App\\', '', get_class($model)))));

        [$sort_key, $sort_dir] = $this->getSortParams();

        \UrlHelper::setSortKey($sort_key)
            ->setDefaultSortDir($this->sort_dir);

        $q = request('q');

        view()->share(compact('model', 'model_tpl', 'q', 'sort_dir', 'sort_key'));
    }

    public function show($id)
    {
        if ($this->api_only && !$this->isApiRequest()) {
            return $this->apiOnlyResponse();
        }

        $model = $this->showGeneric($id);

        $this->modelAccessibleRelations($model);

        if (!$this->isApiRequest()) {
            return view($this->getView(), compact('model'));
        }

        return $this->modelResource($model);
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
        $this->validateArray($this->requestDataForModel(), $this->rules());
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
        $this->validateArray($this->requestDataForModel(), $this->rules($model));
        $this->concurrencyControl($model);

        return $model;
    }

    protected function apiOnlyResponse()
    {
        return view('acp.index');
    }

    protected function appendToCreateAndEditResponse($model): array
    {
        return [];
    }

    protected function appendViewSharedVars(): void
    {
        parent::appendViewSharedVars();

        view()->share([
            'show_with_count' => $this->show_with_count,
        ]);
    }

    protected function concurrencyControl($model)
    {
        if (!request()->has(ConcurrencyControl::FIELD)) {
            return;
        }

        request()->validate([
            ConcurrencyControl::FIELD => [new ConcurrencyControl($model->updated_at)]
        ]);
    }

    protected function sanitizeData(array $data)
    {
        return;
    }

    protected function sanitizeRequest()
    {
        $data = request()->all();

        if (is_array($sanitized_data = $this->sanitizeData($data))) {
            request()->replace($sanitized_data);
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
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @return bool|null
     */
    protected function destroyModel($model)
    {
        if (ModelHelper::hasSoftDeleteLaravel($model) && $model->trashed()) {
            // Первый раз delete(), затем forceDelete()
            return $model->forceDelete();
        } elseif (ModelHelper::hasSoftDeleteIvacuum($model) && !$model->trashed()) {
            // Первый раз softDelete(), затем delete()
            return $model->softDelete();
        }

        return $model->delete();
    }

    /**
     * @param  integer $id
     * @return \Ivacuum\Generic\Models\Model
     */
    protected function getModel($id)
    {
        $model = $this->newModel();

        return $model->where($model->getRouteKeyName(), '=', $id)
            ->when(ModelHelper::hasSoftDeleteLaravel($model), function (Builder $query) {
                return $query->withTrashed();
            })
            ->when($this->method === 'show' && null !== $this->show_with_count, function (Builder $query) {
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
        $sort_dir = request('sd', $this->sort_dir);
        $sort_key = request('sk', $this->sort_key);

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

    protected function isApiRequest()
    {
        return request()->ajax() && !request()->pjax();
    }

    protected function modelAccessibleRelations($model): array
    {
        /* @var \Illuminate\Database\Eloquent\Model $model */
        if (sizeof($this->show_with_count) < 1) {
            return [];
        }

        $me = \Auth::user();
        $result = [];

        foreach ($this->show_with_count as $field) {
            $related = $model->{$field}()->getRelated();

            if (!$me->can('list', $related)) {
                continue;
            }

            $controller = NamingHelper::controllerName($related);
            $count_field = snake_case($field).'_count';
            $count = $model->{$count_field};

            if ($count < 1) {
                continue;
            }

            $path = path("Acp\\{$controller}@index", [$model->getForeignKey() => $model->getKey()]);
            $i18n_index = NamingHelper::transField($related);

            $result[] = compact('path', 'count', 'i18n_index');
        }

        return $result;
    }

    protected function modelResource($model)
    {
        $resource = str_singular(str_replace('Acp\\', 'App\\Http\\Resources\\Acp\\', $this->class));

        return (new $resource($model))
            ->additional([
                'relations' => $this->modelAccessibleRelations($model),
                'i18n_index' => NamingHelper::transField($model),
                'breadcrumbs' => \Breadcrumbs::get(),
            ]);
    }

    protected function modelResourceCollection($models)
    {
        $resource = str_singular(str_replace('Acp\\', 'App\\Http\\Resources\\Acp\\', $this->class)).'Collection';

        return (new $resource($models))
            ->additional(['breadcrumbs' => \Breadcrumbs::get()]);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Model
     */
    protected function newModel()
    {
        $model = $this->getModelName();

        return new $model;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Model
     */
    protected function newModelDefaults($model)
    {
        return $model;
    }

    /**
     * Перенаправление после удаления записи
     *
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @return array
     */
    protected function redirectAfterDestroy($model)
    {
        if (ModelHelper::exists($model)) {
            return [
                'status' => 'OK',
                'redirect' => path("{$this->class}@show", $model),
            ];
        }

        return [
            'status' => 'OK',
            'redirect' => path("{$this->class}@index"),
        ];
    }

    protected function requestDataForModel()
    {
        return request()->except(ConcurrencyControl::FIELD, '_token', 'goto', 'mail');
    }

    protected function rules($model = null)
    {
        return [];
    }

    protected function storeModel()
    {
        $model = $this->newModel()->fill($this->requestDataForModel());
        $model->save();

        return $model;
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     */
    protected function updateModel($model)
    {
        $model->update($this->requestDataForModel());
    }
}
