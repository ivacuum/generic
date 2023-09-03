<?php

namespace Ivacuum\Generic\Scout;

use Foolz\SphinxQL\SphinxQL;
use Ivacuum\Generic\Services\Sphinx;
use Laravel\Scout\Builder;
use Laravel\Scout\Engines\Engine;

class SphinxEngine extends Engine
{
    private SphinxQL $sphinxQl;

    public function __construct(private Sphinx $sphinx)
    {
        $this->sphinxQl = $sphinx->create();
    }

    public function update($models)
    {
        if ($models->isEmpty()) {
            return;
        }

        $model = $models->first();

        $this->ping();

        $query = $this->sphinxQl
            ->replace()
            ->into($model->searchableAs())
            ->columns(array_keys($model->toSearchableArray()));

        $models->each(function ($model) use ($query) {
            $query->values($model->toSearchableArray());
        });

        $query->execute();
    }

    public function delete($models)
    {
        if ($models->isEmpty()) {
            return;
        }

        $model = $models->first();
        $ids = $models->pluck($model->getKeyName())->values()->all();

        $this->ping();

        $this->sphinxQl
            ->delete()
            ->from($model->searchableAs())
            ->where('id', 'IN', $ids)
            ->execute();
    }

    public function search(Builder $builder)
    {
        $this->ping();

        $query = $this->sphinxQl
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

    public function paginate(Builder $builder, $perPage, $page)
    {
        dd('paginate not implemented');
    }

    public function mapIds($results)
    {
        dd('mapIds not implemented');
    }

    public function map(Builder $builder, $results, $model)
    {
        dd('map not implemented');
    }

    public function getTotalCount($results)
    {
        dd('getTotalCount not implemented');
    }

    public function flush($model)
    {
        $this->ping();

        $this->sphinx
            ->helper()
            ->truncateRtIndex($model->searchableAs())
            ->execute();
    }

    public function lazyMap(Builder $builder, $results, $model)
    {
    }

    public function createIndex($name, array $options = [])
    {
    }

    public function deleteIndex($name)
    {
    }

    private function ping()
    {
        $this->sphinxQl->getConnection()->ping();
    }
}
