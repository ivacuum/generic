<?php namespace Ivacuum\Generic\Utilities;

class MetricsHelper
{
    protected $fp;
    protected $metrics;
    protected $timers;

    public function __construct()
    {
        $address = config('cfg.metrics_address');

        if ($address) {
            $this->fp = fsockopen($address);
        }
    }

    public function export()
    {
        if (empty($this->metrics)) {
            return false;
        }

        if (\App::environment('local')) {
            foreach ($this->metrics as $metric) {
                \Log::debug(json_encode($metric));
            }
        }

        if (is_null($this->fp)) {
            return false;
        }

        fwrite($this->fp, json_encode($this->metrics));
        fclose($this->fp);

        return true;
    }

    public function finish($timer)
    {
        $time = round((microtime(true) - $this->timers[$timer]) * 1000, 2);

        $this->pushRaw("Timer {$timer} = {$time}ms");
    }

    public function push(array $data)
    {
        $this->metrics[] = $data;
    }

    public function pushRaw($data)
    {
        $this->metrics[] = ['event' => 'RAW', 'raw' => $data];
    }

    public function start($timer)
    {
        $this->timers[$timer] = microtime(true);
    }
}
