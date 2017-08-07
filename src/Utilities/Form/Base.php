<?php namespace Ivacuum\Generic\Utilities\Form;

use Illuminate\Contracts\Support\Arrayable;

abstract class Base
{
    public $help;
    public $model;
    public $entity = '';
    public $classes = [];

    public function buildData()
    {
        $data = [];

        foreach ((new \ReflectionClass($this))->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) {
            $data[$property->getName()] = $property->getValue($this);
        }

        return $data;
    }

    /**
     * @param  \Illuminate\Contracts\Support\Arrayable|array $values
     * @return $this
     */
    public function classes($values)
    {
        if ($values instanceof Arrayable) {
            $this->classes = $values->toArray();
        } else {
            $this->classes = $values;
        }

        return $this;
    }

    public function help($text)
    {
        $this->help = $text;

        return $this;
    }

    abstract public function html();

    public function model($model)
    {
        $this->model = $model;

        $class = str_replace('App\\', '', get_class($model));

        $this->entity = implode('.', array_map(function ($ary) {
            return snake_case($ary, '-');
        }, explode('\\', $class)));

        return $this;
    }
}
