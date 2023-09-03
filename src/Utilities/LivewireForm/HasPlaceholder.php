<?php

namespace Ivacuum\Generic\Utilities\LivewireForm;

trait HasPlaceholder
{
    public string $placeholder = '';

    public function placeholder(string $value): self
    {
        $this->placeholder = $value;

        return $this;
    }
}
