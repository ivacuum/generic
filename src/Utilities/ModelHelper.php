<?php namespace Ivacuum\Generic\Utilities;

class ModelHelper
{
    /**
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @return bool
     */
    public static function exists($model)
    {
        if (static::hasSoftDeleteLaravel($model)) {
            return !is_null($model::withTrashed()->find($model->getKey()));
        }

        return !is_null($model::find($model->getKey()));
    }

    public static function hasSoftDelete($model)
    {
        return static::hasSoftDeleteLaravel($model) || static::hasSoftDeleteIvacuum($model);
    }

    public static function hasSoftDeleteIvacuum($model)
    {
        return method_exists($model, 'softDelete');
    }

    public static function hasSoftDeleteLaravel($model)
    {
        return method_exists($model, 'bootSoftDeletes');
    }
}
