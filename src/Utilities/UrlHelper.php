<?php namespace Ivacuum\Generic\Utilities;

class UrlHelper
{
    protected $sort_key;
    protected $default_sort_dir;

    public function edit($self, $model)
    {
        return path("$self@edit", [$model, 'goto' => self::go()]);
    }

    public function except(array $params = [])
    {
        return \Request::except(array_merge(['_pjax'], $params));
    }

    public function filter(array $query = [])
    {
        return fullUrl(array_merge([
            'page' => null,
            '_pjax' => null,
        ], $query));
    }

    public function go(array $query = [])
    {
        return fullUrl(array_merge(['_pjax' => null], $query));
    }

    public function setDefaultSortDir($dir)
    {
        $this->default_sort_dir = $dir;

        return $this;
    }

    public function setSortKey($key)
    {
        $this->sort_key = $key;

        return $this;
    }

    public function sort($key, $default_dir = null)
    {
        if (!is_null($this->sort_key) && $this->sort_key != $key) {
            // При смене поля сортировки используется
            // направление сортировки по умолчанию
            $dir = $default_dir === $this->default_sort_dir ? null : $default_dir;
        } else {
            $opposite_dir = $this->default_sort_dir === 'desc' ? 'asc' : 'desc';

            $dir = \Request::input('sd') === $opposite_dir ? null : $opposite_dir;
        }

        return $this->filter([
            'sk' => $key,
            'sd' => $dir,
        ]);
    }
}
