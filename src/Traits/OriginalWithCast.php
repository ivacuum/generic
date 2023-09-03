<?php

namespace Ivacuum\Generic\Traits;

trait OriginalWithCast
{
    public function getOriginalWithCast(string $key)
    {
        if (null === $value = $this->getOriginal($key)) {
            return null;
        }

        if ($this->hasCast($key)) {
            return $this->castAttribute($key, $value);
        }

        return $value;
    }
}
