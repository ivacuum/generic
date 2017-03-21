Общие компоненты для сайтов на Laravel.

### Настройки

`config/app.php`

    'aliases' => [
        ...
        'Form' => Ivacuum\Generic\Facades\Form::class,
        'Breadcrumbs' => Ivacuum\Generic\Facades\Breadcrumbs::class,
    ]

`config/cfg.php`

    'gm_bin' => env('GM_BIN', '/usr/bin/env gm'),

`config/view.php`

    'paths' => [
        ...
        realpath(base_path('vendor/ivacuum/generic/views'))
    ],

