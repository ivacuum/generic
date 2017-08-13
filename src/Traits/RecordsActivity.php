<?php namespace Ivacuum\Generic\Traits;

use Ivacuum\Generic\Utilities\UserAgent;

trait RecordsActivity
{
    protected static function bootRecordsActivity()
    {
        if (auth()->guest()) {
            return;
        }

        foreach (static::getActivitiesToRecord() as $event) {
            static::$event(function ($model) use ($event) {
                $model->recordActivity($event);
            });
        }
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function activities()
    {
        return $this->morphMany(\App\Activity::class, 'rel');
    }

    /**
     * События для отслеживания и сохранения
     *
     * @return array
     */
    protected static function getActivitiesToRecord()
    {
        return ['created', 'deleted', 'updated'];
    }

    /**
     * Сохранение лога действия
     *
     * @param string $event
     */
    protected function recordActivity($event)
    {
        $this->activities()->create([
            'ip' => request()->ip(),
            'type' => $this->getActivityType($event),
            'title' => method_exists($this, 'breadcrumb') ? $this->breadcrumb() : '',
            'user_id' => auth()->id(),
            'user_agent' => UserAgent::tidy(request()->userAgent()),
        ]);
    }

    /**
     * Тип события
     *
     * @param  string $event
     * @return string
     */
    protected function getActivityType($event)
    {
        $type = strtolower((new \ReflectionClass($this))->getShortName());

        return "{$type}.{$event}";
    }
}
