<?php

namespace Ivacuum\Generic\Models;

use Illuminate\Notifications\DatabaseNotification;

/**
 * Уведомление
 *
 * @property int $id
 * @property string $type
 * @property int $notifiable_id
 * @property string $notifiable_type
 * @property string $data
 * @property \Carbon\CarbonImmutable $read_at
 * @property \Carbon\CarbonImmutable $created_at
 * @property \Carbon\CarbonImmutable $updated_at
 *
 * @mixin \Eloquent
 */
class Notification extends DatabaseNotification
{
    protected $keyType = 'string';
    protected $guarded = ['created_at', 'updated_at'];
    protected $perPage = 50;

    public function notifiable()
    {
        return $this->morphTo();
    }

    public function breadcrumb(): string
    {
        return $this->id;
    }

    public function basename(): string
    {
        return \Str::snake(\Str::replaceLast('Notification', '', class_basename($this->type)));
    }
}
