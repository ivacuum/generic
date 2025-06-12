<?php

namespace Ivacuum\Generic\Commands;

use App\Metric;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand('app:metric-rename')]
class MetricRename extends Command
{
    protected $signature = 'app:metric-rename {from} {to}';
    protected $description = 'Rename a metric';

    public function handle()
    {
        $to = $this->argument('to');
        $from = $this->argument('from');

        $metrics = Metric::where('event', $from)
            ->orderBy('date')
            ->get(['date', 'count']);

        $values = [];

        foreach ($metrics as $metric) {
            $values[] = sprintf('("%s", "%s", %d)', $metric->date, $to, $metric->count);
        }

        $rows = count($values);

        \DB::statement('INSERT INTO metrics (`date`, `event`, `count`) VALUES ' . implode(', ', $values) . ' ON DUPLICATE KEY UPDATE `count` = `count` + values(`count`)');

        Metric::where('event', $from)->delete();

        $this->info("Renamed {$from} => {$to} (rows: {$rows})");
    }
}
