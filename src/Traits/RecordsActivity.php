<?php namespace Ivacuum\Generic\Traits;

use Ivacuum\Generic\Utilities\UserAgent;

trait RecordsActivity
{
    protected static function bootRecordsActivity()
    {
        foreach (static::getActivitiesToRecord() as $event) {
            static::$event(fn ($model) => $model->recordActivity($event));
        }

        static::deleting(fn ($model) => $model->activities()->delete());
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function activities()
    {
        return $this->morphMany(\App\Activity::class, 'rel');
    }

    /**
     * Сохранение лога действия
     *
     * @param string $event
     */
    public function recordActivity($event): void
    {
        $this->activities()->create([
            'ip' => request()->ip(),
            'type' => $this->getActivityType($event),
            'title' => method_exists($this, 'breadcrumb') ? $this->breadcrumb() : '',
            'user_id' => auth()->id() ?? 0,
            'user_agent' => UserAgent::tidy(request()->userAgent()),
        ]);
    }

    /**
     * События для отслеживания и сохранения
     *
     * @return array
     */
    protected static function getActivitiesToRecord(): array
    {
        return ['created'];
    }

    /**
     * Тип события
     *
     * @param string $event
     * @return string
     */
    protected function getActivityType(string $event): string
    {
        return "{$this->getMorphClass()}.{$event}";
    }
}
