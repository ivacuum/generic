<?php namespace Ivacuum\Generic\Providers;

use Illuminate\Support\ServiceProvider;

class ValidatorServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->lessOrEqual();
        $this->skype();
        $this->trap();
    }

    protected function lessOrEqual()
    {
        \Validator::extend('less_or_equal', function ($attr, $value, $params) {
            $comparator = \Request::input($params[0], 0);

            return !$comparator || $value <= (int) $comparator;
        });
    }

    protected function skype()
    {
        \Validator::extend('skype', function ($attr, $value, $params) {
            return preg_match('/^[a-z][a-z\d\.,\-\:_]{5,31}$/i', $value);
        });
    }

    /**
     * Обработка доходит до метода только при заполненном значении,
     * то есть всегда провал, если дошло до обработки
     */
    protected function trap()
    {
        \Validator::extend('empty', function ($attr, $value, $params) {
            event(new \Ivacuum\Generic\Events\Stats\SpammerTrapped);

            return false;
        }, 'Читер');
    }
}
