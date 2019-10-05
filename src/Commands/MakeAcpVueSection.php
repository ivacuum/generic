<?php namespace Ivacuum\Generic\Commands;

class MakeAcpVueSection extends MakeAcpSection
{
    protected $signature = 'make:acp-vue-section {model} {base}';
    protected $description = 'Create a new Vue.js admin control panel section';

    protected $fs;
    protected $base;
    protected $model;
    protected $base_plural;
    protected $model_plural;
    protected $base_plural_lower;
    protected $model_plural_lower;

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

        if (!$this->fs->exists($this->resourcePath($this->model))) {
            $this->putResource();
        }

        if (!$this->fs->exists($this->resourceCollectionPath($this->model))) {
            $this->putResourceCollection();
        }

        if ($this->fs->exists($this->componentsPath($this->model_plural))) {
            $this->info("Папка с компонентами [{$this->model_plural}] уже существует");
        } elseif (!$this->fs->exists($this->componentsPath($this->base_plural))) {
            $this->info("Папка с компонентами [{$this->base_plural}] не найдена");
        } else {
            $this->putComponents();
            $this->info("Созданы компоненты [{$this->model_plural}]");
        }

        $this->replaceModelJsTrans();
        $this->printVueRoutes();
        $this->printAuthReminder();
    }

    protected function componentsPath(string $folder): string
    {
        return resource_path("assets/js/components/acp/{$folder}");
    }

    protected function modelJsPath(string $folder): string
    {
        return resource_path("assets/js/components/acp/{$folder}/Model.js");
    }

    protected function printVueRoutes(): void
    {
        if (false !== mb_strpos($this->fs->get($this->routerPath()), "./components/acp/{$this->model_plural}/Index.vue")) {
            return;
        }

        $this->info('Маршруты для вставки в resources/assets/js/router.js');

        echo <<<VUE
{ path: '{$this->model_plural_lower}', component: () => import(/* webpackChunkName: "acp" */'./components/acp/{$this->model_plural}/Index.vue') },
{ path: '{$this->model_plural_lower}/create', component: () => import(/* webpackChunkName: "acp" */'./components/acp/{$this->model_plural}/Form.vue') },
{
  path: '{$this->model_plural_lower}/:id',
  component: () => import(/* webpackChunkName: "acp" */'./components/acp/{$this->model_plural}/Layout.vue'),
  component: () => import(/* webpackChunkName: "acp" */'./components/acp/DefaultItemLayout.vue'),
  children: [
    { path: '/', component: () => import(/* webpackChunkName: "acp" */'./components/acp/{$this->model_plural}/Show.vue') },
    { path: 'edit', component: () => import(/* webpackChunkName: "acp" */'./components/acp/{$this->model_plural}/Form.vue') },
  ],
},

VUE;
    }

    protected function putComponents()
    {
        return $this->fs->copyDirectory(
            $this->componentsPath($this->base_plural),
            $this->componentsPath($this->model_plural)
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
        $path = $this->modelJsPath($this->model_plural);

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
        $var_plural = "{$var}_plural";

        return [
            "Acp\\{$this->{$var_plural}}",
            "App\\{$this->{$var}}",
            "class {$this->{$var}}",
            "{$this->{$var}}::class",
        ];
    }

    protected function routerPath(): string
    {
        return resource_path('assets/js/router.js');
    }
}
