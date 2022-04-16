<?php namespace Ivacuum\Generic\Utilities\LivewireForm;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Htmlable;

abstract class Base implements Htmlable
{
    public $model;
    public bool $lazy = false;
    public bool $required = false;
    public array $classes = [];
    public string $name;
    public string $entity = '';
    public string $camelName;
    public ?string $help = null;
    public ?string $label = null;
    protected $livewire = false;

    public function buildData(): array
    {
        $data = [];
        $properties = (new \ReflectionClass($this))->getProperties(\ReflectionProperty::IS_PUBLIC);

        foreach ($properties as $property) {
            $data[$property->getName()] = $property->getValue($this);
        }

        return $data;
    }

    public function classes($values): self
    {
        if ($values instanceof Arrayable) {
            $this->classes = $values->toArray();
        } else {
            $this->classes = $values;
        }

        return $this;
    }

    public function help(string $text): self
    {
        $this->help = $text;

        return $this;
    }

    abstract public function html();

    public function label(string $text): self
    {
        $this->label = $text;

        return $this;
    }

    public function lazy(): self
    {
        $this->lazy = true;

        return $this;
    }

    public function model($model)
    {
        $this->model = $model;

        $class = str_replace('App\\', '', get_class($model));

        $this->entity = implode('.', array_map(fn ($ary) => \Str::snake($ary, '-'), explode('\\', $class)));

        return $this;
    }

    public function notLazy(): self
    {
        $this->lazy = false;

        return $this;
    }

    public function required(bool $value = true): self
    {
        $this->required = $value;

        return $this;
    }
}
