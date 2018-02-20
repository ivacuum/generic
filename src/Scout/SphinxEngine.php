<?php namespace Ivacuum\Generic\Scout;

use Laravel\Scout\Builder;
use Laravel\Scout\Engines\Engine;

class SphinxEngine extends Engine
{
    /**
     * Update the given model in the index.
     *
     * @param  \Illuminate\Database\Eloquent\Collection $models
     * @return void
     */
    public function update($models)
    {
        if ($models->isEmpty()) {
            return;
        }

        $model = $models->first();

        $query = \Sphinx::create()
            ->replace()
            ->into($model->searchableAs())
            ->columns(array_keys($model->toSearchableArray()));

        $models->each(function ($model) use ($query) {
            $query->values($model->toSearchableArray());
        });

        $query->execute();
    }

    /**
     * Remove the given model from the index.
     *
     * @param  \Illuminate\Database\Eloquent\Collection $models
     * @return void
     */
    public function delete($models)
    {
        if ($models->isEmpty()) {
            return;
        }

        $model = $models->first();
        $ids = $models->pluck($model->getKeyName())->values()->all();

        \Sphinx::create()
            ->delete()
            ->from($model->searchableAs())
            ->where('id', 'IN', $ids)
            ->execute();
    }

    /**
     * Perform the given search on the engine.
     *
     * @param  \Laravel\Scout\Builder $builder
     * @return mixed
     */
    public function search(Builder $builder)
    {
        $query = \Sphinx::create()
            ->select('id')
            ->from($builder->index ?: $builder->model->searchableAs())
            ->limit($builder->limit ?: 500);

        if ($builder->callback) {
            return call_user_func($builder->callback, $query);
        } else {
            return $query->match('*', $builder->query)
                ->execute();
        }
    }

    /**
     * Perform the given search on the engine.
     *
     * @param  \Laravel\Scout\Builder $builder
     * @param  int                    $perPage
     * @param  int                    $page
     * @return mixed
     */
    public function paginate(Builder $builder, $perPage, $page)
    {
        dd('paginate not implemented');
    }

    /**
     * Pluck and return the primary keys of the given results.
     *
     * @param  mixed $results
     * @return \Illuminate\Support\Collection
     */
    public function mapIds($results)
    {
        dd('mapIds not implemented');
    }

    /**
     * Map the given results to instances of the given model.
     *
     * @param  mixed                               $results
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function map($results, $model)
    {
        dd('map not implemented');
    }

    /**
     * Get the total count from a raw result returned by the engine.
     *
     * @param  mixed $results
     * @return int
     */
    public function getTotalCount($results)
    {
        dd('getTotalCount not implemented');
    }
}
