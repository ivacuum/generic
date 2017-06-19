<?php namespace Ivacuum\Generic\Utilities;

class Form
{
    protected $model;

    public function model($model)
    {
        $this->model = $model;

        return $this;
    }

    public function radio(...$parameters)
    {
        return (new Form\Radio(...$parameters))->model($this->model);
    }

    public function text(...$parameters)
    {
        return (new Form\Text(...$parameters))->model($this->model);
    }
}
