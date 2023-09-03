<?php

namespace Ivacuum\Generic\Commands;

use App\Metric;

class MetricDelete extends Command
{
    protected $signature = 'app:metric-delete {metric}';
    protected $description = 'Удаление метрики';

    public function handle()
    {
        $metric = $this->argument('metric');

        $count = Metric::where('event', $metric)->count();

        Metric::where('event', $metric)->delete();

        $this->info("Deleted {$metric} (rows: {$count})");
    }
}
