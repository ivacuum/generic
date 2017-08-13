<?php namespace Ivacuum\Generic\Utilities;

class UserAgent
{
    /**
     * Чистка строки названия браузера от неактуальных подстрок
     *
     * @param  string $ua
     * @return string
     */
    public static function tidy($ua)
    {
        $ua = str_replace([
            '(KHTML, like Gecko) ',
            'Mozilla/4.0 ',
            'Mozilla/5.0 ',
            ' like Gecko',
            'compatible; ',
        ], '', $ua);

        return preg_replace('/(AppleWebKit|Gecko)\/([\d\.]+) /', '', $ua);
    }
}
