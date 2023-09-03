<?php

namespace Ivacuum\Generic\Utilities\LivewireForm;

class Text extends Base
{
    use HasPlaceholder;

    public bool $lazy = true;
    public string $type = 'text';

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function html()
    {
        return view('acp.tpl.livewire.input', $this->buildData());
    }

    public function toHtml()
    {
        return $this->html();
    }
}
