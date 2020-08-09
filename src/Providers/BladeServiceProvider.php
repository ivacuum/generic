<?php namespace Ivacuum\Generic\Providers;

use Illuminate\Support\ServiceProvider;

class BladeServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->lang();
        $this->prop();
        $this->svg();
    }

    protected function lang()
    {
        \Blade::directive('ru', fn () => '<?php if ($locale === "ru"): ?>');
        \Blade::directive('endru', fn () => '<?php endif; ?>');
        \Blade::directive('en', fn () => '<?php elseif ($locale === "en"): ?>');
        \Blade::directive('enden', fn () => '<?php endif; ?>');
        \Blade::directive('de', fn () => '<?php elseif ($locale === "de"): ?>');
        \Blade::directive('endde', fn () => '<?php endif; ?>');
        \Blade::directive('endlang', fn () => '<?php endif; ?>');
    }

    protected function prop()
    {
        \Blade::directive('prop', fn () => '<?php echo \Ivacuum\Generic\Utilities\Vue::prop($expression); ?>');
    }

    protected function svg()
    {
        \Blade::directive('svg', fn ($expression) => "<?php require base_path(\"resources/svg/$expression.svg\"); ?>");
    }
}
