<?php namespace Ivacuum\Generic\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

/**
 * Электронное письмо
 *
 * @property integer $id
 * @property integer $user_id
 * @property string  $from
 * @property string  $to
 * @property string  $template
 * @property string  $text
 * @property string  $token
 * @property integer $clicks
 * @property integer $views
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 *
 * @property \App\User $user
 *
 * @mixin \Eloquent
 */
class Email extends Model
{
    const TIMESTAMP_FORMAT = 'YmdHis';
    const TOKEN_LENGTH = 6;

    protected $guarded = ['created_at'];
    protected $perPage = 50;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function breadcrumb(): string
    {
        return "Письмо #{$this->id}";
    }

    public function getTimestamp(): string
    {
        return $this->created_at->format(self::TIMESTAMP_FORMAT);
    }

    public function tokenLink($goto): string
    {
        return action('Mail@click', [
            $this->getTimestamp(),
            $this->id,
            $this->token,
            'goto' => $goto,
        ]);
    }
}
