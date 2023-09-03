<?php

namespace Ivacuum\Generic\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

/**
 * Учетка внешнего сервиса
 *
 * @property int $id
 * @property int $user_id
 * @property string $provider
 * @property string $uid
 * @property string $email
 * @property \Carbon\CarbonImmutable $created_at
 * @property \Carbon\CarbonImmutable $updated_at
 * @property \App\User $user
 *
 * @mixin \Eloquent
 */
class ExternalIdentity extends Model
{
    public const VK = 'vk';
    public const GITHUB = 'github';
    public const GOOGLE = 'google';
    public const YANDEX = 'yandex';
    public const TWITTER = 'twitter';
    public const FACEBOOK = 'facebook';

    protected $guarded = ['created_at', 'updated_at'];
    protected $perPage = 50;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function breadcrumb(): string
    {
        return $this->email ?: ($this->user_id ? $this->user->email : "#{$this->id}");
    }

    public function externalLink(): string
    {
        return match ($this->provider) {
            'facebook' => "https://www.facebook.com/{$this->uid}",
            'google' => "https://plus.google.com/{$this->uid}",
            'odnoklassniki' => "https://ok.ru/profile/{$this->uid}",
            'twitter' => "https://twitter.com/intent/user?user_id={$this->uid}",
            'vk' => "https://vk.com/id{$this->uid}",
            default => '#',
        };
    }
}
