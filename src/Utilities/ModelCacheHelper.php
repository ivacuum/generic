<?php namespace Ivacuum\Generic\Utilities;

use Carbon\CarbonInterval;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Вспомогательный класс для упрощения работы с кэшированными данными моделей
 *
 * До использования необходимо объявить в экземляре класса:
 * - const CACHED_BY_ID_KEY
 * - const CACHED_BY_SLUG_KEY
 */
abstract class ModelCacheHelper
{
    protected $model;
    protected $idField = 'id';
    protected $orderBy = 'title';
    protected $cachedId;
    protected $slugField = 'slug';
    protected $cachedSlug;
    protected $titleField = 'title';
    protected $cachedFields = ['*'];
    protected $rememberTime = 1440;

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function cachedById()
    {
        return \Cache::remember($this->cachedByIdKey(), CarbonInterval::minutes($this->rememberTime), function () {
            return $this->model->where($this->slugField, '<>', '')
                ->orderBy($this->orderBy)
                ->get($this->cachedFields)
                ->keyBy($this->idField);
        });
    }

    abstract public function cachedByIdKey(): string;

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function cachedBySlug()
    {
        return \Cache::remember($this->cachedBySlugKey(), CarbonInterval::minutes($this->rememberTime), function () {
            return $this->model->where($this->slugField, '<>', '')
                ->orderBy($this->orderBy)
                ->get($this->cachedFields)
                ->keyBy($this->slugField);
        });
    }

    abstract public function cachedBySlugKey(): string;

    public function findById(int $id)
    {
        if ($this->cachedId === null) {
            $this->cachedId = $this->cachedById();
        }

        return isset($this->cachedId[$id]) ? $this->cachedId[$id] : null;
    }

    public function findByIdOrFail(int $id)
    {
        $result = $this->findById($id);

        if ($result !== null) {
            return $result;
        }

        throw (new ModelNotFoundException)->setModel(
            get_class($this->model), $id
        );
    }

    public function findBySlug(?string $slug)
    {
        if (!$slug) {
            return null;
        }

        if ($this->cachedSlug === null) {
            $this->cachedSlug = $this->cachedBySlug();
        }

        return isset($this->cachedSlug[$slug]) ? $this->cachedSlug[$slug] : null;
    }

    public function findBySlugOrFail(?string $slug)
    {
        $result = $this->findBySlug($slug);

        if ($result !== null) {
            return $result;
        }

        throw (new ModelNotFoundException)->setModel(
            get_class($this->model), $slug
        );
    }

    public function title($q): ?string
    {
        return is_numeric($q)
            ? optional($this->findById($q))->{$this->titleField}
            : optional($this->findBySlug($q))->{$this->titleField};
    }
}
