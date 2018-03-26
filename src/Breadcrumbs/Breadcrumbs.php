<?php namespace Ivacuum\Generic\Breadcrumbs;

class Breadcrumbs
{
    protected $breadcrumbs = [];

    public function get(): array
    {
        return $this->breadcrumbs;
    }

    public function pop(): self
    {
        array_pop($this->breadcrumbs);

        return $this;
    }

    public function push(string $title, ?string $url = null): self
    {
        $this->breadcrumbs[] = compact('title', 'url');

        return $this;
    }
}
