<?php namespace Ivacuum\Generic\Utilities;

use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Вспомогательный класс для упрощения работы с кэшированными данными моделей
 *
 * До использования необходимо объявить в экземляре класса:
 * - const CACHED_BY_ID_KEY
 * - const CACHED_BY_SLUG_KEY
 * - protected static $cached_id
 * - protected static $cached_slug
 */
abstract class ModelCacheHelper
{
    protected $model;
    protected $id_field = 'id';
    protected $order_by = 'title';
    protected $slug_field = 'slug';
    protected $title_field = 'title';
    protected $cached_fields = ['*'];
    protected $remember_time = 1440;

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function cachedById()
    {
        return \Cache::remember(static::CACHED_BY_ID_KEY, $this->remember_time, function () {
            return $this->model->where($this->slug_field, '<>', '')
                ->orderBy($this->order_by)
                ->get($this->cached_fields)
                ->keyBy($this->id_field);
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function cachedBySlug()
    {
        return \Cache::remember(static::CACHED_BY_SLUG_KEY, $this->remember_time, function () {
            return $this->model->where($this->slug_field, '<>', '')
                ->orderBy($this->order_by)
                ->get($this->cached_fields)
                ->keyBy($this->slug_field);
        });
    }

    public function findById(int $id)
    {
        if (static::$cached_id === null) {
            static::$cached_id = $this->cachedById();
        }

        return isset(static::$cached_id[$id]) ? static::$cached_id[$id] : null;
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

        if (static::$cached_slug === null) {
            static::$cached_slug = $this->cachedBySlug();
        }

        return isset(static::$cached_slug[$slug]) ? static::$cached_slug[$slug] : null;
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
            ? optional($this->findById($q))->{$this->title_field}
            : optional($this->findBySlug($q))->{$this->title_field};
    }
}
