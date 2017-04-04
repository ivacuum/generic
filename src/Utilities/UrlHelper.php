<?php namespace Ivacuum\Generic\Utilities;

class UrlHelper
{
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
}
