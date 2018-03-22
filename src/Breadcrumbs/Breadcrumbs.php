<?php namespace Ivacuum\Generic\Breadcrumbs;

use Illuminate\Support\HtmlString;

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

    public function pushHtml(HtmlString $title, ?string $url = null): self
    {
        $this->breadcrumbs[] = compact('title', 'url');

        return $this;
    }
}
