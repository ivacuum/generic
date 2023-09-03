<?php

namespace Ivacuum\Generic\Utilities;

class Vue
{
    public static function prop($name, $value): string
    {
        return sprintf(":%s='%s'", $name, json_encode($value));
    }
}
