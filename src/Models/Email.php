<?php namespace Ivacuum\Generic\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

/**
 * Электронное письмо
 *
 * @property integer $id
 * @property integer $user_id
 * @property string  $to
 * @property string  $template
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

    protected $guarded = ['created_at'];
    protected $perPage = 50;

    // Relations
    public function rel()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Methods
    public function breadcrumb(): string
    {
        return "Письмо #{$this->id}";
    }

    public function getTimestamp(): string
    {
        return $this->created_at->format(static::TIMESTAMP_FORMAT);
    }

    public function hasValidTimestamp(string $timestamp): bool
    {
        return $timestamp === $this->getTimestamp();
    }

    public function reportLink(): string
    {
        return path('Mail@report', [
            $this->getTimestamp(),
            $this->id,
        ]);
    }

    public function signedLink(string $goto, $expiration = null): string
    {
        return \URL::signedRoute('mail.click', [
            $this->getTimestamp(),
            $this->id,
            'goto' => $goto,
        ], $expiration);
    }
}
