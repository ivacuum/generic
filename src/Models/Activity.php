<?php namespace Ivacuum\Generic\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Действия пользователей
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $rel_id
 * @property string  $rel_type
 * @property string  $type
 * @property string  $title
 * @property string  $ip
 * @property string  $user_agent
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 *
 * @mixin \Eloquent
 */
class Activity extends Model
{
    protected $guarded = [];

    public function rel()
    {
        return $this->morphTo();
    }
}
