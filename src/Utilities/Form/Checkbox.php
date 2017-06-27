<?php namespace Ivacuum\Generic\Utilities\Form;

class Checkbox extends Base
{
    public $name;
    public $type = 'checkbox';
    public $values = [];
    public $default;
    public $required = false;

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function default($value)
    {
        $this->default = $value;

        return $this;
    }

    public function html()
    {
        return view('acp.tpl.input', $this->buildData());
    }

    public function required($value = true)
    {
        $this->required = $value === true ? true : false;

        return $this;
    }

    public function values(array $values)
    {
        $this->values = $values;

        return $this;
    }
}
