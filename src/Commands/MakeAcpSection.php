<?php

namespace Ivacuum\Generic\Commands;

use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand('make:acp-section', 'Create a new admin control panel section')]
class MakeAcpSection extends Command
{
    protected $signature = 'make:acp-section {model} {base}';

    protected $fs;
    protected $base;
    protected $model;
    protected $basePlural;
    protected $modelPlural;
    protected $basePluralLower;
    protected $modelPluralLower;

    public function __construct(Filesystem $fs)
    {
        parent::__construct();

        $this->fs = $fs;
    }

    public function handle()
    {
        $this->base = $this->argument('base');
        $this->model = $this->argument('model');
        $this->basePlural = \Str::plural($this->base);
        $this->modelPlural = \Str::plural($this->model);
        $this->basePluralLower = mb_strtolower($this->basePlural);
        $this->modelPluralLower = mb_strtolower($this->modelPlural);

        if ($this->fs->exists($this->controllerPath($this->modelPlural))) {
            $this->info("Контроллер [{$this->modelPlural}] уже существует");
        } elseif (!$this->fs->exists($this->controllerPath($this->basePlural))) {
            $this->info("Контроллер [{$this->basePlural}] не найден");
        } else {
            $this->putController();
            $this->info("Создан контроллер [{$this->modelPlural}]");
        }

        $this->putRoute();

        if ($this->fs->exists($this->viewsPath($this->modelPluralLower))) {
            $this->info("Папка с шаблонами [{$this->modelPluralLower}] уже существует");
        } elseif (!$this->fs->exists($this->viewsPath($this->basePluralLower))) {
            $this->info("Папка с шаблонами [{$this->basePluralLower}] не найдена");
        } else {
            $this->putViews();
            $this->info("Созданы шаблоны [{$this->modelPluralLower}]");
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
        $varPlural = "{$var}_plural";

        return [
            "use App\\{$this->{$var}} as Model;",
            "class {$this->{$varPlural}} ",
        ];
    }

    protected function printAuthReminder(): void
    {
        if (str_contains($this->fs->get($this->authServiceProviderPath()), "{$this->model}::class")) {
            return;
        }

        $this->info('Файл app/Providers/AuthServiceProvider.php нужно отредактировать вручную');
    }

    protected function putController(): void
    {
        $content = str_replace(
            $this->controllerReplaceArray('base'),
            $this->controllerReplaceArray('model'),
            $this->fs->get($this->controllerPath($this->basePlural))
        );

        $this->fs->put($this->controllerPath($this->modelPlural), $content);
    }

    protected function putRoute(): void
    {
        $path = $this->routesPath();
        $content = $this->fs->get($path);

        if (preg_match('/.*Acp\\\\' . $this->modelPlural . '.*/', $content)) {
            $this->info("Маршрут для контроллера [{$this->modelPlural}] уже существует");
        } elseif (preg_match_all('/.*Acp\\\\' . $this->basePlural . '.*/', $content, $matches)) {
            $routes = "\n";

            foreach ($matches[0] as $match) {
                $route = str_replace([
                    "\\{$this->basePlural}",
                    mb_strtolower($this->basePlural) . '/',
                ], [
                    "\\{$this->modelPlural}",
                    mb_strtolower($this->modelPlural) . '/',
                ], $match);

                $routes .= $route . "\n";

                $this->info($route);
            }

            $this->fs->put($path, $content . $routes);
        }
    }

    protected function putViews(): bool
    {
        return $this->fs->copyDirectory(
            $this->viewsPath($this->basePluralLower),
            $this->viewsPath($this->modelPluralLower)
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
