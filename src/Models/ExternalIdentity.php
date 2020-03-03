<?php namespace Ivacuum\Generic\Models;

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
 *
 * @property \App\User $user
 *
 * @mixin \Eloquent
 */
class ExternalIdentity extends Model
{
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
        switch ($this->provider) {
            case 'facebook':
                return "https://www.facebook.com/{$this->uid}";
            case 'google':
                return "https://plus.google.com/{$this->uid}";
            case 'odnoklassniki':
                return "https://ok.ru/profile/{$this->uid}";
            case 'twitter':
                return "https://twitter.com/intent/user?user_id={$this->uid}";
            case 'vk':
                return "https://vk.com/id{$this->uid}";
        }

        return '#';
    }
}
