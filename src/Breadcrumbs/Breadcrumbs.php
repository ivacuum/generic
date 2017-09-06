<?php namespace Ivacuum\Generic\Breadcrumbs;

class Breadcrumbs
{
    protected $breadcrumbs = [];

    public function get(): array
    {
        return $this->breadcrumbs;
    }

    public function push($title, $url = null): self
    {
        $this->breadcrumbs[] = compact('title', 'url');

        return $this;
    }
}
