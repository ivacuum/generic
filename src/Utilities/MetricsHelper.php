<?php namespace Ivacuum\Generic\Utilities;

class MetricsHelper
{
    protected $fp;
    protected $local = false;
    protected $timers;
    protected $console = false;
    protected $metrics;

    public function __construct()
    {
        $address = config('cfg.metrics_address');

        $this->fp = $address ? fsockopen($address) : null;

        $this->local = \App::isLocal();
        $this->console = \App::runningInConsole();

        $this->setupExport(\App::environment());
    }

    public function export(): bool
    {
        if (empty($this->metrics)) {
            return false;
        }

        if ($this->local) {
            foreach ($this->metrics as $metric) {
                \Log::debug(json_encode($metric));
            }
        }

        if (!is_null($this->fp)) {
            fwrite($this->fp, json_encode($this->metrics));

            // При веб-сервере отправка всех событий происходит разом в конце,
            // поэтому можно закрыть соединение
            // В терминале же по мере поступления запросов, поэтому закрывать нельзя
            if (!$this->console) {
                fclose($this->fp);
            }
        }

        $this->resetMetrics();

        return true;
    }

    public function finish(string $timer): void
    {
        $time = round((microtime(true) - $this->timers[$timer]) * 1000, 2);

        $this->pushRaw("Timer {$timer} = {$time}ms");
    }

    public function push(array $data): void
    {
        $this->metrics[] = $data;

        if ($this->console) {
            $this->export();
        }
    }

    public function pushRaw(string $data): void
    {
        $this->metrics[] = ['event' => 'RAW', 'raw' => $data];

        if ($this->console) {
            $this->export();
        }
    }

    public function resetMetrics(): void
    {
        $this->metrics = [];
    }

    public function setupExport(string $environment): void
    {
        if (in_array($environment, ['local', 'production']) && !$this->console) {
            register_shutdown_function(function () {
                $this->export();
            });
        }
    }

    public function start(string $timer): void
    {
        $this->timers[$timer] = microtime(true);
    }
}
