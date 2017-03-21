Общие компоненты для сайтов на Laravel.

### Локальная разработка

`composer.json` родительского проекта

    "repositories": [
      {
        "type": "path",
        "url": "~/Sites/generic",
        "options": {
          "symlink": true
        }
      }
    ],


### Настройки

`config/app.php`

    'aliases' => [
        ...
        'Form' => Ivacuum\Generic\Facades\Form::class,
        'Breadcrumbs' => Ivacuum\Generic\Facades\Breadcrumbs::class,
    ]

`config/cfg.php`

    'gm_bin' => env('GM_BIN', '/usr/bin/env gm'),
    'metrics_address' => 'udp://127.0.0.1:1111',

`config/view.php`

    'paths' => [
        ...
        realpath(base_path('vendor/ivacuum/generic/views'))
    ],

