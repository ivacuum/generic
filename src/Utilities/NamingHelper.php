<?php namespace Ivacuum\Generic\Utilities;

class NamingHelper
{
    /**
     * App\PostPhoto => PostPhotos
     *
     * @param object $class
     * @return string
     */
    public static function controllerName($class): string
    {
        return \Str::plural(class_basename($class));
    }

    // PostPhotos\FunnyEvents => post-photos.funny-events
    public static function kebab(string $classString): string
    {
        return implode('.', array_map(function ($item) {
            return \Str::kebab($item);
        }, explode('\\', $classString)));
    }

    /**
     * App\PostPhoto => post-photos
     *
     * @param object $class
     * @return string
     */
    public static function transField($class): string
    {
        return static::kebab(static::controllerName($class));
    }
}
