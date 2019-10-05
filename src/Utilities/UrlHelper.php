<?php namespace Ivacuum\Generic\Utilities;

class UrlHelper
{
    protected $sortKey;
    protected $defaultSortDir;

    public function edit(string $controller, $model): string
    {
        return path(
            [$controller, 'edit'],
            [$model, 'goto' => static::go() . "#{$model->getRouteKeyName()}-{$model->getRouteKey()}"]
        );
    }

    public function except(array $params = []): array
    {
        return \Request::except(array_merge(['_pjax'], $params));
    }

    public function filter(array $query = []): string
    {
        return fullUrl(array_merge([
            'page' => null,
            '_pjax' => null,
        ], $query));
    }

    public function go(array $query = []): string
    {
        return fullUrl(array_merge(['_pjax' => null], $query));
    }

    public function setDefaultSortDir(string $dir): self
    {
        $this->defaultSortDir = $dir;

        return $this;
    }

    public function setSortKey(string $key): self
    {
        $this->sortKey = $key;

        return $this;
    }

    public function sort(string $key, ?string $defaultDir = null): string
    {
        if (null !== $this->sortKey && $this->sortKey !== $key) {
            // При смене поля сортировки используется
            // направление сортировки по умолчанию
            $dir = $defaultDir === $this->defaultSortDir ? null : $defaultDir;
        } else {
            $oppositeDir = $this->defaultSortDir === 'desc' ? 'asc' : 'desc';

            $dir = \Request::input('sd') === $oppositeDir ? null : $oppositeDir;
        }

        return $this->filter([
            'sk' => $key,
            'sd' => $dir,
        ]);
    }
}
