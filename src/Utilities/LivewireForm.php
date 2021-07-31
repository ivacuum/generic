<?php namespace Ivacuum\Generic\Utilities;

class LivewireForm
{
    protected $model;

    public function model($model)
    {
        $this->model = $model;

        return $this;
    }

    public function checkbox(...$parameters): LivewireForm\Checkbox
    {
        return (new LivewireForm\Checkbox(...$parameters))->model($this->model);
    }

    public function radio(...$parameters): LivewireForm\Radio
    {
        return (new LivewireForm\Radio(...$parameters))->model($this->model);
    }

    public function select(...$parameters): LivewireForm\Select
    {
        return (new LivewireForm\Select(...$parameters))->model($this->model);
    }

    public function text(...$parameters): LivewireForm\Text
    {
        return (new LivewireForm\Text(...$parameters))->model($this->model);
    }

    public function textarea(...$parameters): LivewireForm\Textarea
    {
        return (new LivewireForm\Textarea(...$parameters))->model($this->model);
    }
}
