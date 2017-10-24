<?php namespace Ivacuum\Generic\Utilities\Form;

use Illuminate\Contracts\Support\Arrayable;

class Radio extends Base
{
    public $name;
    public $type = 'radio';
    public $values = [];
    public $required = false;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function html()
    {
        return view('acp.tpl.input', $this->buildData());
    }

    public function required(bool $value = true): self
    {
        $this->required = $value;

        return $this;
    }

    /**
     * @param  \Illuminate\Contracts\Support\Arrayable|array $values
     * @return $this
     */
    public function values($values): self
    {
        if ($values instanceof Arrayable) {
            $this->values = $values->toArray();
        } else {
            $this->values = $values;
        }

        return $this;
    }
}
