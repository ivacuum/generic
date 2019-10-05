<?php namespace Ivacuum\Generic\Controllers\Acp;

use Illuminate\Database\Eloquent\Builder;
use Ivacuum\Generic\Rules\ConcurrencyControl;
use Ivacuum\Generic\Utilities\ModelHelper;
use Ivacuum\Generic\Utilities\NamingHelper;

class Controller extends BaseController
{
    protected $apiOnly = false;
    protected $sortDir = 'desc';
    protected $sortKey = 'id';
    protected $showWith = [];
    protected $sortableKeys = ['id'];
    protected $showWithCount = [];
    protected $reactiveFields = [];

    public function create()
    {
        if ($this->shouldReturnApiOnlyResponse()) {
            return $this->apiOnlyResponse();
        }

        $model = $this->createGeneric();

        if (!$this->isApiRequest()) {
            return view($this->getAcpView(), ['model' => $model]);
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
        if ($this->shouldReturnApiOnlyResponse()) {
            return $this->apiOnlyResponse();
        }

        $model = $this->editGeneric($id);

        if (!$this->isApiRequest()) {
            return view($this->getAcpView(), ['model' => $model]);
        }

        return array_merge(
            ['model' => $model],
            ['breadcrumbs' => \Breadcrumbs::get()],
            $model->exists && $model->updated_at ? [ConcurrencyControl::FIELD => md5($model->updated_at)] : [],
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
        if ($this->shouldReturnApiOnlyResponse()) {
            return $this->apiOnlyResponse();
        }

        $model = $this->newModel();

        $this->authorize('list', $model);

        $modelTpl = implode('.', array_map(function ($ary) {
            return \Str::snake($ary, '-');
        }, explode('\\', str_replace('App\\', '', get_class($model)))));

        [$sortKey, $sortDir] = $this->getSortParams();

        \UrlHelper::setSortKey($sortKey)
            ->setDefaultSortDir($this->sortDir);

        $q = request('q');

        view()->share([
            'q' => $q,
            'model' => $model,
            'sortDir' => $sortDir,
            'sortKey' => $sortKey,
            'modelTpl' => $modelTpl,
        ]);

        return null;
    }

    public function show($id)
    {
        if ($this->shouldReturnApiOnlyResponse()) {
            return $this->apiOnlyResponse();
        }

        $model = $this->showGeneric($id);

        if (!$this->isApiRequest()) {
            return view($this->getAcpView(), [
                'model' => $model,
                'modelRelations' => $this->modelAccessibleRelations($model),
            ]);
        }

        return $this->modelResource($model);
    }

    public function showGeneric($id)
    {
        $model = $this->getModel($id);

        $this->authorize('show', $model);

        \Breadcrumbs::push($model->breadcrumb());

        view()->share(['metaTitle' => $model->breadcrumb()]);

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
            'showWithCount' => $this->showWithCount,
        ]);
    }

    protected function concurrencyControl($model)
    {
        if (!request()->has(ConcurrencyControl::FIELD)) {
            return;
        }

        request()->validate([
            ConcurrencyControl::FIELD => [new ConcurrencyControl($model->updated_at)],
        ]);
    }

    protected function sanitizeData(array $data)
    {
        return null;
    }

    protected function sanitizeRequest()
    {
        $data = request()->all();

        if (is_array($sanitizeData = $this->sanitizeData($data))) {
            request()->replace($sanitizeData);
        }
    }

    /**
     * @param \Ivacuum\Generic\Models\Model|\Eloquent $model
     */
    protected function breadcrumbsModelSubpage($model)
    {
        \Breadcrumbs::push(
            $model->breadcrumb(),
            str_replace('.', '/', $this->prefix) . "/{$model->getRouteKey()}"
        );

        \Breadcrumbs::push(trans($this->view));
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model $model
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

    protected function getAcpView(): string
    {
        return view()->exists($this->view) ? $this->view : "acp.{$this->method}";
    }

    /**
     * @param int $id
     * @return \Ivacuum\Generic\Models\Model
     */
    protected function getModel($id)
    {
        $model = $this->newModel();

        return $model->where($model->getRouteKeyName(), '=', $id)
            ->when(ModelHelper::hasSoftDeleteLaravel($model), function (Builder $query) {
                return $query->withTrashed();
            })
            ->when($this->method === 'show' && sizeof($this->showWith), function (Builder $query) {
                return $query->with($this->showWith);
            })
            ->when($this->method === 'show' && sizeof($this->showWithCount), function (Builder $query) {
                return $query->withCount($this->showWithCount);
            })
            ->firstOrFail();
    }

    protected function getModelName(): string
    {
        return 'App\\' . \Str::singular(class_basename($this));
    }

    protected function getSortParams()
    {
        $sortDir = request('sd', $this->sortDir);
        $sortKey = request('sk', $this->sortKey);

        if (!in_array($sortDir, ['asc', 'desc'])) {
            $sortDir = $this->sortDir;
        }

        if (!in_array($sortKey, $this->sortableKeys)) {
            $sortKey = $this->sortKey;
        }

        return [$sortKey, $sortDir];
    }

    protected function isApiRequest()
    {
        return request()->ajax() && !request()->pjax();
    }

    protected function modelAccessibleRelations($model): array
    {
        /** @var \Illuminate\Database\Eloquent\Model $model */
        if (sizeof($this->showWithCount) < 1) {
            return [];
        }

        $me = \Auth::user();
        $result = [];

        foreach ($this->showWithCount as $field) {
            $related = $model->{$field}()->getRelated();

            if (!$me->can('list', $related)) {
                continue;
            }

            $controller = NamingHelper::controllerName($related);
            $countField = \Str::snake($field) . '_count';
            $count = $model->{$countField};

            if ($count < 1) {
                continue;
            }

            $result[] = [
                'path' => path("Acp\\{$controller}@index", [$model->getForeignKey() => $model->getKey()]),
                'count' => $count,
                'i18n_index' => NamingHelper::transField($related),
            ];
        }

        return $result;
    }

    protected function modelResource($model)
    {
        $resource = 'App\\Http\\Resources\\Acp\\' . \Str::singular(class_basename($this));

        return (new $resource($model))
            ->additional([
                'relations' => $this->modelAccessibleRelations($model),
                'i18n_index' => NamingHelper::transField($model),
                'breadcrumbs' => \Breadcrumbs::get(),
            ]);
    }

    protected function modelResourceCollection($models)
    {
        $resource = 'App\\Http\\Resources\\Acp\\' . \Str::singular(class_basename($this)) . 'Collection';

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
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return \Illuminate\Database\Eloquent\Model
     */
    protected function newModelDefaults($model)
    {
        foreach ($this->reactiveFields as $field) {
            $model->{$field} = null;
        }

        return $model;
    }

    /**
     * Перенаправление после удаления записи
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return array
     */
    protected function redirectAfterDestroy($model)
    {
        if (ModelHelper::exists($model)) {
            return [
                'status' => 'OK',
                'redirect' => path([$this->controller, 'show'], $model),
            ];
        }

        return [
            'status' => 'OK',
            'redirect' => path([$this->controller, 'index']),
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

    protected function shouldReturnApiOnlyResponse(): bool
    {
        return $this->apiOnly && !$this->isApiRequest();
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
