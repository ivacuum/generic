<?php namespace Ivacuum\Generic\Utilities;

class NamingHelper
{
    /**
     * App\PostPhoto => PostPhotos
     *
     * @param  object $class
     * @return string
     */
    public static function controllerName($class): string
    {
        return str_plural(class_basename($class));
    }

    /**
     * PostPhotos\FunnyEvents => post-photos.funny-events
     *
     * @param  string $class_string
     * @return string
     */
    public static function kebab(string $class_string): string
    {
        return implode('.', array_map(function ($item) {
            return kebab_case($item);
        }, explode('\\', $class_string)));
    }

    /**
     * App\PostPhoto => post-photos
     *
     * @param  object $class
     * @return string
     */
    public static function transField($class): string
    {
        return self::kebab(self::controllerName($class));
    }
}
