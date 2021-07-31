<?php namespace Ivacuum\Generic\Commands;

class MakeAcpVueSection extends MakeAcpSection
{
    protected $signature = 'make:acp-vue-section {model} {base}';
    protected $description = 'Create a new Vue.js admin control panel section';

    protected $fs;
    protected $base;
    protected $model;
    protected $basePlural;
    protected $modelPlural;
    protected $basePluralLower;
    protected $modelPluralLower;

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

        if (!$this->fs->exists($this->resourcePath($this->model))) {
            $this->putResource();
        }

        if (!$this->fs->exists($this->resourceCollectionPath($this->model))) {
            $this->putResourceCollection();
        }

        if ($this->fs->exists($this->componentsPath($this->modelPlural))) {
            $this->info("Папка с компонентами [{$this->modelPlural}] уже существует");
        } elseif (!$this->fs->exists($this->componentsPath($this->basePlural))) {
            $this->info("Папка с компонентами [{$this->basePlural}] не найдена");
        } else {
            $this->putComponents();
            $this->info("Созданы компоненты [{$this->modelPlural}]");
        }

        $this->replaceModelJsTrans();
        $this->printVueRoutes();
        $this->printAuthReminder();
    }

    protected function componentsPath(string $folder): string
    {
        return resource_path("js/components/acp/{$folder}");
    }

    protected function modelJsPath(string $folder): string
    {
        return resource_path("js/components/acp/{$folder}/Model.js");
    }

    protected function printVueRoutes(): void
    {
        if (str_contains($this->fs->get($this->routerPath()), "./components/acp/{$this->modelPlural}/Index.vue")) {
            return;
        }

        $this->info('Маршруты для вставки в resources/js/router.js');

        echo <<<VUE
{ path: '{$this->modelPluralLower}', component: () => import(/* webpackChunkName: "acp" */'./components/acp/{$this->modelPlural}/Index.vue') },
{ path: '{$this->modelPluralLower}/create', component: () => import(/* webpackChunkName: "acp" */'./components/acp/{$this->modelPlural}/Form.vue') },
{
  path: '{$this->modelPluralLower}/:id',
  component: () => import(/* webpackChunkName: "acp" */'./components/acp/{$this->modelPlural}/Layout.vue'),
  component: () => import(/* webpackChunkName: "acp" */'./components/acp/DefaultItemLayout.vue'),
  children: [
    { path: '/', component: () => import(/* webpackChunkName: "acp" */'./components/acp/{$this->modelPlural}/Show.vue') },
    { path: 'edit', component: () => import(/* webpackChunkName: "acp" */'./components/acp/{$this->modelPlural}/Form.vue') },
  ],
},

VUE;
    }

    protected function putComponents()
    {
        return $this->fs->copyDirectory(
            $this->componentsPath($this->basePlural),
            $this->componentsPath($this->modelPlural)
        );
    }

    protected function putResource()
    {
        $content = str_replace(
            $this->resourceReplaceArray('base'),
            $this->resourceReplaceArray('model'),
            $this->fs->get($this->resourcePath($this->base))
        );

        $this->fs->put($this->resourcePath($this->model), $content);
    }

    protected function putResourceCollection()
    {
        $content = str_replace(
            $this->resourceReplaceArray('base'),
            $this->resourceReplaceArray('model'),
            $this->fs->get($this->resourceCollectionPath($this->base))
        );

        $this->fs->put($this->resourceCollectionPath($this->model), $content);
    }

    protected function replaceModelJsTrans()
    {
        $path = $this->modelJsPath($this->modelPlural);

        $this->fs->put($path, preg_replace('/\''.$this->base.'\'/', "'{$this->model}'", $this->fs->get($path)));
    }

    protected function resourceCollectionPath(string $file): string
    {
        return app_path("Http/Resources/Acp/{$file}Collection.php");
    }

    protected function resourcePath(string $file): string
    {
        return app_path("Http/Resources/Acp/{$file}.php");
    }

    protected function resourceReplaceArray(string $var): array
    {
        $varPlural = "{$var}_plural";

        return [
            "Acp\\{$this->{$varPlural}}",
            "App\\{$this->{$var}}",
            "class {$this->{$var}}",
            "{$this->{$var}}::class",
        ];
    }

    protected function routerPath(): string
    {
        return resource_path('js/router.js');
    }
}
