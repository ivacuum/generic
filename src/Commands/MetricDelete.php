<?php

namespace Ivacuum\Generic\Commands;

use App\Metric;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand('app:metric-delete', 'Delete a metric')]
class MetricDelete extends Command
{
    protected $signature = 'app:metric-delete {metric}';

    public function handle()
    {
        $metric = $this->argument('metric');

        $count = Metric::where('event', $metric)->count();

        Metric::where('event', $metric)->delete();

        $this->info("Deleted {$metric} (rows: {$count})");
    }
}
