<?php namespace Ivacuum\Generic\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Действия пользователей
 *
 * @property int $id
 * @property int $user_id
 * @property int $rel_id
 * @property string $rel_type
 * @property string $type
 * @property string $title
 * @property string $ip
 * @property string $user_agent
 * @property \Carbon\CarbonImmutable $created_at
 * @property \Carbon\CarbonImmutable $updated_at
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
