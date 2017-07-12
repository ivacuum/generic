<?php namespace Ivacuum\Generic\Utilities;

class UrlHelper
{
    protected $sort_key;

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

    public function setSortKey($key)
    {
        $this->sort_key = $key;

        return $this;
    }

    public function sort($key, $default_dir = 'desc')
    {
        if (!is_null($this->sort_key) && $this->sort_key != $key) {
            // При смене поля сортировки используется
            // направление сортировки по умолчанию
            $dir = $default_dir === 'desc' ? null : $default_dir;
        } else {
            $dir = \Request::input('sd') === 'asc' ? null : 'asc';
        }

        return $this->filter([
            'sk' => $key,
            'sd' => $dir,
        ]);
    }
}
