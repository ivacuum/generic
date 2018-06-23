<?php namespace Ivacuum\Generic\Traits;

trait SoftDeleteTrait
{
    public function softDelete(): bool
    {
        $this->{$this->getStatusDeletedColumn()} = $this->getStatusDeletedValue();

        return $this->save();
    }

    public function trashed()
    {
        return (int) $this->{$this->getStatusDeletedColumn()} === (int) $this->getStatusDeletedValue();
    }

    public function getStatusDeletedColumn(): string
    {
        return defined('static::STATUS_DELETED_COLUMN') ? static::STATUS_DELETED_COLUMN : 'status';
    }

    public function getStatusDeletedValue(): int
    {
        if (!defined('static::STATUS_DELETED')) {
            throw new \Exception('Не найден STATUS_DELETED у модели');
        }

        return static::STATUS_DELETED;
    }
}
