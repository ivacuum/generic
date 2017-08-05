<?php namespace Ivacuum\Generic\Utilities;

class Form
{
    protected $model;

    public function model($model)
    {
        $this->model = $model;

        return $this;
    }

    /**
     * @param  array ...$parameters
     * @return \Ivacuum\Generic\Utilities\Form\Checkbox
     */
    public function checkbox(...$parameters)
    {
        return (new Form\Checkbox(...$parameters))->model($this->model);
    }

    /**
     * @param  array ...$parameters
     * @return \Ivacuum\Generic\Utilities\Form\Radio
     */
    public function radio(...$parameters)
    {
        return (new Form\Radio(...$parameters))->model($this->model);
    }

    /**
     * @param  array ...$parameters
     * @return \Ivacuum\Generic\Utilities\Form\Select
     */
    public function select(...$parameters)
    {
        return (new Form\Select(...$parameters))->model($this->model);
    }

    /**
     * @param  array ...$parameters
     * @return \Ivacuum\Generic\Utilities\Form\Text
     */
    public function text(...$parameters)
    {
        return (new Form\Text(...$parameters))->model($this->model);
    }

    /**
     * @param  array ...$parameters
     * @return \Ivacuum\Generic\Utilities\Form\Textarea
     */
    public function textarea(...$parameters)
    {
        return (new Form\Textarea(...$parameters))->model($this->model);
    }
}
