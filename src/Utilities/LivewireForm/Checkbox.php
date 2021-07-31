<?php namespace Ivacuum\Generic\Utilities\LivewireForm;

class Checkbox extends Base
{
    use HasManyValues;

    public string $type = 'checkbox';

    public function __construct(string $name)
    {
        $this->name = $name;
        $this->camelName = \Str::camel($name);
    }

    public function html()
    {
        return view('acp.tpl.livewire.input', $this->buildData());
    }
}
