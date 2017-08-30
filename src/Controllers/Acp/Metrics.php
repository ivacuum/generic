<?php namespace Ivacuum\Generic\Controllers\Acp;

use App\Metric as Model;
use Illuminate\Support\Carbon;

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

        return view($this->view, compact('dates', 'events', 'metrics'));
    }

    public function show($event)
    {
        \Breadcrumbs::push($event);

        $metrics = Model::where('event', $event)->get();
        $first_day = sizeof($metrics) ? Carbon::parse($metrics->first()->date) : now();
        $last_day = sizeof($metrics) ? Carbon::parse($metrics->last()->date) : now();
        $metrics = $metrics->pluck('count', 'date');

        return view($this->view, compact('event', 'first_day', 'last_day', 'metrics'));
    }
}
