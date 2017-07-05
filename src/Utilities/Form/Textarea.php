<?php namespace Ivacuum\Generic\Utilities\Form;

class Textarea extends Base
{
    public $name;
    public $type = 'textarea';
    public $required = false;
    public $placeholder = '';

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function html()
    {
        return view('acp.tpl.input', $this->buildData());
    }

    public function placeholder($value)
    {
        $this->placeholder = $value;

        return $this;
    }

    public function required($value = true)
    {
        $this->required = $value === true ? true : false;

        return $this;
    }
}
