<?php namespace Ivacuum\Generic\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Уведомление
 *
 * @property int $id
 * @property string $type
 * @property int $notifiable_id
 * @property string $notifiable_type
 * @property string $data
 * @property \Illuminate\Support\Carbon $read_at
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 *
 * @mixin \Eloquent
 */
class Notification extends Model
{
    protected $keyType = 'string';
    protected $guarded = ['created_at', 'updated_at'];
    protected $dates = ['read_at'];
    protected $perPage = 50;

    public function notifiable()
    {
        return $this->morphTo();
    }

    public function breadcrumb(): string
    {
        return $this->id;
    }
}
