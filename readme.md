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

### Маршруты для входа через социалки

`routes/web.php`

    Route::get('auth/facebook', 'Auth\Facebook@index');
    Route::get('auth/facebook/callback', 'Auth\Facebook@callback');
    Route::get('auth/google', 'Auth\Google@index');
    Route::get('auth/google/callback', 'Auth\Google@callback');
    Route::get('auth/odnoklassniki', 'Auth\Odnoklassniki@index');
    Route::get('auth/odnoklassniki/callback', 'Auth\Odnoklassniki@callback');
    Route::get('auth/vk', 'Auth\Vk@index');
    Route::get('auth/vk/callback', 'Auth\Vk@callback');

### Настройки

`config/app.php`

    'aliases' => [
        ...
        'Form' => Ivacuum\Generic\Facades\Form::class,
        'Breadcrumbs' => Ivacuum\Generic\Facades\Breadcrumbs::class,
    ],

`config/cfg.php`

    'gm_bin' => env('GM_BIN', '/usr/bin/env gm'),
    'metrics_address' => 'udp://127.0.0.1:1111',

`config/view.php`

    'paths' => [
        ...
        realpath(base_path('vendor/ivacuum/generic/views'))
    ],
