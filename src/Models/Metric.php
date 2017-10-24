<?php namespace Ivacuum\Generic\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Метрика
 *
 * @property \Illuminate\Support\Carbon $date
 * @property string  $event
 * @property integer $count
 *
 * @mixin \Eloquent
 */
class Metric extends Model
{
    protected $guarded = ['*'];
    protected $perPage = 100;

    public function scopeWeek(Builder $query)
    {
        return $query->where('date', '>', now()->subWeek()->toDateString());
    }

    public static function possibleMetrics(): array
    {
        foreach (glob(app_path('Events/Stats/*.php')) as $file) {
            $events[] = pathinfo($file, PATHINFO_FILENAME);
        }

        foreach (glob(base_path('vendor/ivacuum/generic/src/Events/Stats/*.php')) as $file) {
            $events[] = pathinfo($file, PATHINFO_FILENAME);
        }

        asort($events);

        return $events ?? [];
    }
}
