<?php namespace Ivacuum\Generic\Traits;

trait SoftDeleteTrait
{
    public function softDelete()
    {
        $query = $this->newQueryWithoutScopes()->where($this->getKeyName(), $this->getKey());

        $this->{$this->getStatusDeletedColumn()} = $this->getStatusDeletedValue();

        $columns = [$this->getStatusDeletedColumn() => $this->getStatusDeletedValue()];

        if ($this->timestamps) {
            $time = $this->freshTimestamp();

            $this->{$this->getUpdatedAtColumn()} = $time;

            $columns[$this->getUpdatedAtColumn()] = $this->fromDateTime($time);
        }

        $query->update($columns);
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
