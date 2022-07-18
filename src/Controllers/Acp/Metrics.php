<?php namespace Ivacuum\Generic\Controllers\Acp;

use App\Metric as Model;
use Carbon\Carbon;

class Metrics extends BaseController
{
    public function index()
    {
        $events = Model::possibleMetrics();
        $metrics = $dates = [];

        Model::week()->get()->map(function ($item) use (&$metrics, &$dates) {
            $dates[$item->date] = true;
            $metrics[$item->event][$item->date] = $item->count;
        });

        return view($this->view, [
            'dates' => $dates,
            'events' => $events,
            'metrics' => $metrics,
        ]);
    }

    public function show($event)
    {
        \Breadcrumbs::push($event);

        $metrics = Model::where('event', $event)->get();
        $lastDay = count($metrics) ? Carbon::parse($metrics->last()->date) : now();
        $firstDay = count($metrics) ? Carbon::parse($metrics->first()->date) : now();

        return view($this->view, [
            'event' => $event,
            'lastDay' => $lastDay,
            'metrics' => $metrics->pluck('count', 'date'),
            'firstDay' => $firstDay,
        ]);
    }
}
