<?php namespace Ivacuum\Generic\Utilities\Form;

class Textarea extends Base
{
    public $name;
    public $type = 'textarea';
    public $wide = false;
    public $required = false;
    public $placeholder = '';

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function html()
    {
        $tpl = $this->wide ? 'input-textarea-wide' : 'input';

        return view("acp.tpl.$tpl", $this->buildData());
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

    public function wide($value = true)
    {
        $this->wide = $value === true ? true : false;

        return $this;
    }
}
