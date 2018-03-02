Общие компоненты для сайтов на Laravel.

### Локальная разработка

`composer.json` родительского проекта

```json
"repositories": [
  {
    "type": "path",
    "url": "~/Sites/generic",
    "options": {
      "symlink": true
    }
  }
],
```

### Маршруты для входа через социалки

`routes/web.php`

```php
Route::get('auth/facebook', 'Auth\Facebook@index');
Route::get('auth/facebook/callback', 'Auth\Facebook@callback');
Route::get('auth/google', 'Auth\Google@index');
Route::get('auth/google/callback', 'Auth\Google@callback');
Route::get('auth/odnoklassniki', 'Auth\Odnoklassniki@index');
Route::get('auth/odnoklassniki/callback', 'Auth\Odnoklassniki@callback');
Route::get('auth/vk', 'Auth\Vk@index');
Route::get('auth/vk/callback', 'Auth\Vk@callback');
```

### Настройки

`config/app.php`

```php
'providers' => [
    ...
    // До сервис провайдеров проекта
    // Для поддержки локализаций
    Ivacuum\Generic\Providers\LocaleServiceProvider::class,
],
```

`config/cfg.php`

```php
'gm_bin' => env('GM_BIN', '/usr/bin/env gm'),
'locales' => [
    'ru' => ['posix' => 'ru_RU.UTF-8'],
    'en' => ['posix' => 'en_US.UTF-8'],
],
'metrics_address' => 'udp://127.0.0.1:1111',
'sphinx' => [
    'host'   => env('SPHINX_HOST', 'localhost'),
    'port'   => env('SPHINX_PORT', 9306),
    'socket' => env('SPHINX_SOCKET', ''),
],
'telegram' => [
    'admin_id' => env('TELEGRAM_ADMIN_ID'),
],
```

`config/view.php`

```php
'paths' => [
    ...
    realpath(base_path('vendor/ivacuum/generic/views'))
],
```
