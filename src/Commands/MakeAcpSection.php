<?php namespace Ivacuum\Generic\Commands;

use Illuminate\Filesystem\Filesystem;

class MakeAcpSection extends Command
{
    protected $signature = 'make:acp-section {model} {base}';
    protected $description = 'Create a new admin control panel section';

    protected $fs;
    protected $base;
    protected $model;
    protected $base_plural;
    protected $model_plural;
    protected $base_plural_lower;
    protected $model_plural_lower;

    public function __construct(Filesystem $fs)
    {
        parent::__construct();

        $this->fs = $fs;
    }

    public function handle()
    {
        $this->base = $this->argument('base');
        $this->model = $this->argument('model');
        $this->base_plural = \Str::plural($this->base);
        $this->model_plural = \Str::plural($this->model);
        $this->base_plural_lower = mb_strtolower($this->base_plural);
        $this->model_plural_lower = mb_strtolower($this->model_plural);

        if ($this->fs->exists($this->controllerPath($this->model_plural))) {
            $this->info("Контроллер [{$this->model_plural}] уже существует");
        } elseif (!$this->fs->exists($this->controllerPath($this->base_plural))) {
            $this->info("Контроллер [{$this->base_plural}] не найден");
        } else {
            $this->putController();
            $this->info("Создан контроллер [{$this->model_plural}]");
        }

        $this->putRoute();

        if ($this->fs->exists($this->viewsPath($this->model_plural_lower))) {
            $this->info("Папка с шаблонами [{$this->model_plural_lower}] уже существует");
        } elseif (!$this->fs->exists($this->viewsPath($this->base_plural_lower))) {
            $this->info("Папка с шаблонами [{$this->base_plural_lower}] не найдена");
        } else {
            $this->putViews();
            $this->info("Созданы шаблоны [{$this->model_plural_lower}]");
        }

        $this->printAuthReminder();
    }

    protected function authServiceProviderPath(): string
    {
        return app_path('Providers/AuthServiceProvider.php');
    }

    protected function controllerPath(string $file): string
    {
        return app_path("Http/Controllers/Acp/{$file}.php");
    }

    protected function controllerReplaceArray(string $var): array
    {
        $var_plural = "{$var}_plural";

        return [
            "use App\\{$this->{$var}} as Model;",
            "class {$this->{$var_plural}} ",
        ];
    }

    protected function printAuthReminder(): void
    {
        if (false !== mb_strpos($this->fs->get($this->authServiceProviderPath()), "{$this->model}::class")) {
            return;
        }

        $this->info('Файл app/Providers/AuthServiceProvider.php нужно отредактировать вручную');
    }

    protected function putController(): void
    {
        $content = str_replace(
            $this->controllerReplaceArray('base'),
            $this->controllerReplaceArray('model'),
            $this->fs->get($this->controllerPath($this->base_plural))
        );

        $this->fs->put($this->controllerPath($this->model_plural), $content);
    }

    protected function putRoute(): void
    {
        $path = $this->routesPath();
        $content = $this->fs->get($path);

        if (preg_match('/.*Acp\\\\'.$this->model_plural.'.*/', $content)) {
            $this->info("Маршрут для контроллера [{$this->model_plural}] уже существует");
        } elseif (preg_match_all('/.*Acp\\\\'.$this->base_plural.'.*/', $content, $matches)) {
            $routes = "\n";

            foreach ($matches[0] as $match) {
                $route = str_replace([
                    "\\{$this->base_plural}",
                    mb_strtolower($this->base_plural).'/',
                ], [
                    "\\{$this->model_plural}",
                    mb_strtolower($this->model_plural).'/',
                ], $match);

                $routes .= $route . "\n";

                $this->info($route);
            }

            $this->fs->put($path, $content.$routes);
        }
    }

    protected function putViews(): bool
    {
        return $this->fs->copyDirectory(
            $this->viewsPath($this->base_plural_lower),
            $this->viewsPath($this->model_plural_lower)
        );
    }

    protected function routesPath(): string
    {
        return base_path('routes/acp.php');
    }

    protected function viewsPath(string $file): string
    {
        return resource_path("views/acp/{$file}");
    }
}
