<?php namespace Ivacuum\Generic\Controllers\Acp;

use Illuminate\Database\Eloquent\Builder;
use Ivacuum\Generic\Rules\ConcurrencyControl;
use Ivacuum\Generic\Utilities\ModelHelper;
use Ivacuum\Generic\Utilities\NamingHelper;

class Controller extends BaseController
{
    protected $sortDir = 'desc';
    protected $sortKey = 'id';
    protected $showWith = [];
    protected $sortableKeys = ['id'];
    protected $showWithCount = [];

    public function create()
    {
        $model = $this->createGeneric();

        return view($this->getAcpView(), ['model' => $model]);
    }

    public function createGeneric()
    {
        $model = $this->newModel();

        $this->authorize('create', $model);

        \Breadcrumbs::push(__($this->view));

        return $model;
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

        $this->authorize('delete', $model);

        return $model;
    }

    public function edit($id)
    {
        $model = $this->editGeneric($id);

        return view($this->getAcpView(), ['model' => $model]);
    }

    public function editGeneric($id)
    {
        $model = $this->getModel($id);

        $this->authorize('update', $model);
        $this->breadcrumbsModelSubpage($model);

        return $model;
    }

    public function indexBefore()
    {
        $model = $this->newModel();

        $this->authorize('viewAny', $model);

        $modelTpl = implode('.', array_map(fn ($ary) => \Str::snake($ary, '-'), explode('\\', str_replace('App\\', '', get_class($model)))));

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
        $model = $this->showGeneric($id);

        return view($this->getAcpView(), [
            'model' => $model,
            'modelRelations' => $this->modelAccessibleRelations($model),
        ]);
    }

    public function showGeneric($id)
    {
        $model = $this->getModel($id);

        $this->authorize('view', $model);

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

        $this->authorize('update', $model);
        $this->validateArray($this->requestDataForModel(), $this->rules($model));
        $this->concurrencyControl($model);

        return $model;
    }

    protected function alwaysCallBefore()
    {
        if ($this->method === 'index' && method_exists($this, 'indexBefore')) {
            return $this->indexBefore();
        }

        return null;
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

    protected function breadcrumbsModelSubpage($model)
    {
        \Breadcrumbs::push(
            $model->breadcrumb(),
            str_replace('.', '/', $this->prefix) . "/{$model->getRouteKey()}"
        );

        \Breadcrumbs::push(__($this->view));
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
        if (view()->exists($this->view)) {
            return $this->view;
        }

        if (in_array($this->method, ['create', 'edit']) && $this instanceof UsesLivewire) {
            return "acp.livewire-{$this->method}";
        }

        return "acp.{$this->method}";
    }

    /**
     * @param int $id
     * @return \Ivacuum\Generic\Models\Model
     */
    protected function getModel($id)
    {
        $model = $this->newModel();

        return $model->query()
            ->where($model->getRouteKeyName(), '=', $id)
            ->when(ModelHelper::hasSoftDeleteLaravel($model), fn (Builder $query) => $query->withTrashed())
            ->when($this->method === 'show' && sizeof($this->showWith), fn (Builder $query) => $query->with($this->showWith))
            ->when($this->method === 'show' && sizeof($this->showWithCount), fn (Builder $query) => $query->withCount($this->showWithCount))
            ->firstOrFail();
    }

    protected function getModelName(): string
    {
        return NamingHelper::modelClassFromController(static::class);
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

            if (!$me->can('viewAny', $related)) {
                continue;
            }

            $controller = NamingHelper::controllerName($related);
            $countField = \Str::snake($field) . '_count';
            $count = $model->{$countField};

            if ($count < 1) {
                continue;
            }

            $result[] = [
                'path' => path(
                    ["App\Http\Controllers\Acp\\{$controller}", 'index'],
                    [$model->getForeignKey() => $model->getKey()]
                ),
                'count' => $count,
                'i18n_index' => NamingHelper::transField($related),
            ];
        }

        return $result;
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
                'redirect' => path([static::class, 'show'], $model),
            ];
        }

        return [
            'status' => 'OK',
            'redirect' => path([static::class, 'index']),
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
