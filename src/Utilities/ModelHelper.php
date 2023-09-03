<?php

namespace Ivacuum\Generic\Utilities;

class ModelHelper
{
    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     */
    public static function exists($model): bool
    {
        if (static::hasSoftDeleteLaravel($model)) {
            return null !== $model::withTrashed()->find($model->getKey());
        }

        return null !== $model::find($model->getKey());
    }

    public static function hasSoftDelete($model): bool
    {
        return static::hasSoftDeleteLaravel($model) || static::hasSoftDeleteIvacuum($model);
    }

    public static function hasSoftDeleteIvacuum($model): bool
    {
        return method_exists($model, 'softDelete');
    }

    public static function hasSoftDeleteLaravel($model): bool
    {
        return method_exists($model, 'bootSoftDeletes');
    }
}
