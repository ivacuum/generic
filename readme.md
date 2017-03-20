Общие компоненты для сайтов на Laravel.

### Настройки

`config/app.php`

    'aliases' => [
        ...
        'Breadcrumbs' => Ivacuum\Generic\Facades\Breadcrumbs::class,
    ]

`config/view.php`

    'paths' => [
        ...
        realpath(base_path('vendor/ivacuum/generic/views'))
    ],

