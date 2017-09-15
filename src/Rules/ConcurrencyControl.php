<?php namespace Ivacuum\Generic\Rules;

use Illuminate\Contracts\Validation\Rule;

class ConcurrencyControl implements Rule
{
    const FIELD = '_concurrency_control';

    protected $key;

    public function __construct($key)
    {
        $this->key = md5($key);
    }

    public function passes($attribute, $value)
    {
        return $this->key === $value;
    }

    public function message()
    {
        return 'Объект был кем-то изменен до сохранения ваших правок. Вы можете: 1) отменить свои правки — обновить страницу или перейти на другую 2) объединить изменения — в соседней вкладке воспроизвести свои правки 3) перезаписать чужие правки — повторно сохранить изменения.';
    }
}
